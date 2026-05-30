"use client";

import { useState, useEffect } from "react";
import { useAuth } from "../context/AuthContext";
import { api, virtualDb } from "@/services/api";
import { createPortal } from "react-dom";
import { useTranslation } from "@/context/LanguageContext";

export default function GoogleDriveSync() {
  const { user } = useAuth();
  const { t, lang } = useTranslation();
  
  // Status: "synced" (green), "pending" (amber), "syncing" (pulsing blue), "error" (red), "not_authorized" (grey)
  const [syncStatus, setSyncStatus] = useState("synced");
  const [accessToken, setAccessToken] = useState(null);
  const [showSyncModal, setShowSyncModal] = useState(false);
  const [lastSyncDate, setLastSyncDate] = useState(null);
  const [syncLogs, setSyncLogs] = useState([]);
  const [mounted, setMounted] = useState(false);
  
  const isSimulated = user?.auth_type?.includes("simulated");
  const clientId = process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID;

  // Add log entry
  const addLog = (msg) => {
    setSyncLogs(prev => [`[${new Date().toLocaleTimeString()}] ${msg}`, ...prev.slice(0, 15)]);
  };

  // Load sync metadata from localStorage on mount
  useEffect(() => {
    setMounted(true);
    const savedMeta = localStorage.getItem(`slotrace_sync_meta_${user?.email}`);
    if (savedMeta) {
      const meta = JSON.parse(savedMeta);
      setLastSyncDate(meta.lastSyncDate);
      setSyncStatus(meta.status || "synced");
    } else {
      setSyncStatus("pending"); // new users start as pending
    }
    
    // Restore sessionStorage token if exists
    const savedToken = sessionStorage.getItem("slotrace_drive_token");
    if (savedToken) {
      setAccessToken(savedToken);
    }
  }, [user?.email]);

  // Save metadata changes
  const saveMeta = (status, date) => {
    setSyncStatus(status);
    if (date) setLastSyncDate(date);
    localStorage.setItem(`slotrace_sync_meta_${user?.email}`, JSON.stringify({
      status,
      lastSyncDate: date || lastSyncDate
    }));
  };

  // Listen to changes in the database to mark status as "pending"
  useEffect(() => {
    const handleDbChange = () => {
      if (syncStatus === "synced") {
        saveMeta("pending");
      }
    };

    window.addEventListener("slotrace_db_changed", handleDbChange);
    return () => window.removeEventListener("slotrace_db_changed", handleDbChange);
  }, [syncStatus]);

  // Request real Google Drive OAuth Access Token
  const authorizeDrive = () => {
    if (isSimulated || !clientId) {
      addLog("Simulator Mode: Cloud authorization granted.");
      setAccessToken("mock_access_token");
      sessionStorage.setItem("slotrace_drive_token", "mock_access_token");
      if (typeof window !== "undefined") {
        window.dispatchEvent(new Event("slotrace_token_updated"));
      }
      return Promise.resolve("mock_access_token");
    }

    return new Promise((resolve) => {
      try {
        const tokenClient = window.google?.accounts.oauth2.initTokenClient({
          client_id: clientId,
          scope: "https://www.googleapis.com/auth/drive.file",
          callback: (response) => {
            if (response.error) {
              addLog(`Authorization error: ${response.error}`);
              resolve(null);
            } else if (response.access_token) {
              setAccessToken(response.access_token);
              sessionStorage.setItem("slotrace_drive_token", response.access_token);
              addLog("Access granted to Google Drive.");
              if (typeof window !== "undefined") {
                window.dispatchEvent(new Event("slotrace_token_updated"));
              }
              resolve(response.access_token);
            }
          },
        });
        tokenClient.requestAccessToken();
      } catch (err) {
        addLog(`Error initializing Token Client: ${err.message}`);
        resolve(null);
      }
    });
  };

  // Safe fetch wrapper that injects Authorization headers and retries once on 401 token expiry
  const driveFetch = async (url, options = {}, tokenOverride = null) => {
    let token = tokenOverride || accessToken;
    if (!token) {
      token = await authorizeDrive();
    }
    if (!token) {
      throw new Error("Authorization pending or rejected.");
    }

    const headers = {
      ...options.headers,
      Authorization: `Bearer ${token}`
    };

    let res = await fetch(url, { ...options, headers });
    
    if (res.status === 401) {
      addLog("Connection expired on Google Drive. Renewing credentials...");
      const newToken = await authorizeDrive();
      if (newToken) {
        headers.Authorization = `Bearer ${newToken}`;
        res = await fetch(url, { ...options, headers });
      } else {
        throw new Error("Google session expired. Please authorize again.");
      }
    }
    
    return res;
  };

  // Perform Cloud Backup (Export local database to Google Drive)
  const handleBackup = async () => {
    setSyncStatus("syncing");
    addLog("Starting catalog packaging...");

    try {
      // 1. Get raw export data from the virtual db
      const rawData = virtualDb.getExportData();

      const backupPayload = {
        ...rawData,
        backup_at: new Date().toISOString(),
        user_email: user?.email
      };

      addLog(`Packaged: ${rawData.drivers.length} drivers, ${rawData.cars.length} cars, ${rawData.tracks.length} tracks.`);

      // 2. Obtain token or call drive
      let token = accessToken;
      if (!token) {
        token = await authorizeDrive();
      }
      if (!token) {
        throw new Error("Authorization pending or rejected.");
      }

      if (isSimulated || token === "mock_access_token") {
        // MOCK SYNC SIMULATION
        addLog("Simulating sending of 'slotrace_backup.json' file...");
        await new Promise(r => setTimeout(r, 1500));
        localStorage.setItem(`mock_drive_file_${user?.email}`, JSON.stringify(backupPayload));
        addLog("Simulated cloud successfully updated!");
      } else {
        // REAL GOOGLE DRIVE API CALLS
        addLog("Searching for existing backup file on your Google Drive...");
        
        // Search if slotrace_backup.json exists
        const searchRes = await driveFetch(
          `https://www.googleapis.com/drive/v3/files?q=name='slotrace_backup.json' and trashed=false`
        );
        const searchData = await searchRes.json();
        const existingFile = searchData.files?.[0];

        let uploadRes;
        if (existingFile) {
          addLog("Updating existing backup file...");
          // Update File Content: PATCH request
          uploadRes = await driveFetch(
            `https://www.googleapis.com/upload/drive/v3/files/${existingFile.id}?uploadType=media`,
            {
              method: "PATCH",
              headers: {
                "Content-Type": "application/json"
              },
              body: JSON.stringify(backupPayload)
            }
          );
        } else {
          addLog("Creating new backup file in Google Drive...");
          // Create File: Multipart POST
          const metadata = {
            name: "slotrace_backup.json",
            mimeType: "application/json"
          };
          
          const form = new FormData();
          form.append("metadata", new Blob([JSON.stringify(metadata)], { type: "application/json" }));
          form.append("file", new Blob([JSON.stringify(backupPayload)], { type: "application/json" }));

          uploadRes = await driveFetch(
            "https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart",
            {
              method: "POST",
              body: form
            }
          );
        }

        if (!uploadRes.ok) {
          const errData = await uploadRes.json().catch(() => ({}));
          throw new Error(errData.error?.message || "HTTP communication failure with Drive.");
        }

        addLog("Backup upload completed successfully!");
      }

      const syncDateStr = new Date().toLocaleString();
      saveMeta("synced", syncDateStr);
      addLog("Synchronization finished successfully.");
    } catch (err) {
      console.warn("Error backing up to Google Drive:", err);
      addLog(`Error during backup: ${err.message}`);
      saveMeta("error");
    }
  };

  // Perform Cloud Restore (Download backup from Drive and populate local database)
  const handleRestore = async () => {
    if (!confirm(t.driveSync.restoreConfirm)) {
      return;
    }

    setSyncStatus("syncing");
    addLog("Starting backup recovery...");

    try {
      let token = accessToken;
      if (!token) {
        token = await authorizeDrive();
      }
      if (!token) {
        throw new Error("Authorization pending or rejected.");
      }

      let backupPayload = null;

      if (isSimulated || token === "mock_access_token") {
        // MOCK SYNC SIMULATION
        addLog("Simulating download of 'slotrace_backup.json' file...");
        await new Promise(r => setTimeout(r, 1200));
        const fileContent = localStorage.getItem(`mock_drive_file_${user?.email}`);
        if (!fileContent) {
          throw new Error("No backup file found in the Drive simulator.");
        }
        backupPayload = JSON.parse(fileContent);
      } else {
        // REAL GOOGLE DRIVE API CALLS
        addLog("Searching for backup file on your Google Drive...");
        const searchRes = await driveFetch(
          `https://www.googleapis.com/drive/v3/files?q=name='slotrace_backup.json' and trashed=false`
        );
        const searchData = await searchRes.json();
        const existingFile = searchData.files?.[0];

        if (!existingFile) {
          throw new Error("No 'slotrace_backup.json' backup file found on your Google Drive.");
        }

        addLog("Downloading consolidated data...");
        const downloadRes = await driveFetch(
          `https://www.googleapis.com/drive/v3/files/${existingFile.id}?alt=media`
        );

        if (!downloadRes.ok) {
          throw new Error("Failed to download data from Google Drive.");
        }

        backupPayload = await downloadRes.json();
      }

      addLog("Clearing local database for import...");
      
      // Import the complete backup directly into our local Virtual Database cache
      virtualDb.importData(backupPayload);

      addLog(`Restore completed: ${backupPayload.drivers?.length || 0} drivers and ${backupPayload.races?.length || 0} races imported!`);
      const syncDateStr = new Date().toLocaleString();
      saveMeta("synced", syncDateStr);
      
      // Trigger dynamic page refreshes
      window.dispatchEvent(new Event("slotrace_data_restored"));
      alert(t.driveSync.restoreSuccess);
    } catch (err) {
      console.warn("Error restoring from Google Drive:", err);
      addLog(`Error during restoration: ${err.message}`);
      saveMeta("error");
      alert(`Error restoring data: ${err.message}`);
    }
  };

  // Debounced Automatic Background Backup trigger
  useEffect(() => {
    if (syncStatus === "pending") {
      // Only execute auto backup if we have a valid token (so we don't pop up the auth window silently)
      // or if we are in simulator mode
      if (accessToken || isSimulated) {
        const delay = 3000; // 3 seconds debounce
        const timer = setTimeout(() => {
          addLog("Local changes detected. Syncing with Cloud...");
          handleBackup();
        }, delay);
        return () => clearTimeout(timer);
      }
    }
  }, [syncStatus, accessToken, isSimulated]);

  // Automatic Cloud Restore on successful login / session active
  useEffect(() => {
    const autoRestoreOnLogin = async () => {
      if (!accessToken || !user?.email) return;
      
      // Avoid loops: check if we have already restored in this session
      const alreadyRestored = sessionStorage.getItem(`slotrace_drive_auto_restored_${user.email}`);
      if (alreadyRestored) return;

      try {
        setSyncStatus("syncing");
        addLog("Auto-sync: Checking for cloud backup on Google Drive...");
        
        let backupPayload = null;

        if (isSimulated || accessToken === "mock_access_token") {
          const fileContent = localStorage.getItem(`mock_drive_file_${user.email}`);
          if (fileContent) {
            backupPayload = JSON.parse(fileContent);
          }
        } else {
          const searchRes = await driveFetch(
            `https://www.googleapis.com/drive/v3/files?q=name='slotrace_backup.json' and trashed=false`,
            {},
            accessToken
          );
          const searchData = await searchRes.json();
          const existingFile = searchData.files?.[0];

          if (existingFile) {
            addLog("Auto-sync: Downloading backup file...");
            const downloadRes = await driveFetch(
              `https://www.googleapis.com/drive/v3/files/${existingFile.id}?alt=media`,
              {},
              accessToken
            );
            if (downloadRes.ok) {
              backupPayload = await downloadRes.json();
            }
          }
        }

        if (backupPayload) {
          addLog("Auto-sync: Restoring cloud data...");
          virtualDb.importData(backupPayload);
          const syncDateStr = new Date().toLocaleString();
          saveMeta("synced", syncDateStr);
          addLog("Auto-sync: Restore completed successfully.");
        } else {
          addLog("Auto-sync: No cloud backup found. Starting fresh.");
          saveMeta("synced", new Date().toLocaleString());
        }
        
        // Mark as auto-restored for this session to prevent duplicate runs on route changes
        sessionStorage.setItem(`slotrace_drive_auto_restored_${user.email}`, "true");
      } catch (err) {
        console.warn("Auto-sync restore failed:", err);
        addLog(`Auto-sync error: ${err.message}`);
        saveMeta("error");
      }
    };

    autoRestoreOnLogin();
  }, [accessToken, user?.email]);

  // Event listener to open modal programmatically
  useEffect(() => {
    const handleOpenSync = () => {
      setShowSyncModal(true);
      // Trigger authorization prompt when opening modal if not yet authorized
      if (!accessToken) {
        authorizeDrive();
      }
    };
    window.addEventListener("slotrace_open_sync", handleOpenSync);
    return () => window.removeEventListener("slotrace_open_sync", handleOpenSync);
  }, [accessToken]);

  return (
    <>
      {/* Dynamic pulsing cloud icon button */}
      <button
        onClick={() => setShowSyncModal(true)}
        className="flex items-center justify-center p-1.5 rounded-lg hover:bg-white/5 border border-white/5 transition-all text-slate-400 hover:text-white"
        title="Google Drive Backup & Sync"
      >
        {syncStatus === "synced" && (
          <div className="relative flex items-center justify-center">
            <svg className="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
            </svg>
            <span className="absolute -top-0.5 -right-0.5 w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping" />
          </div>
        )}

        {syncStatus === "pending" && (
          <div className="relative flex items-center justify-center">
            <svg className="w-5 h-5 text-amber-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span className="absolute -top-0.5 -right-0.5 w-1.5 h-1.5 rounded-full bg-amber-400" />
          </div>
        )}

        {syncStatus === "syncing" && (
          <svg className="w-5 h-5 text-cyan-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 4.75L21.21 9M9 13h12m0 0l-4-4m4 4l-4 4" />
          </svg>
        )}

        {syncStatus === "error" && (
          <svg className="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        )}
      </button>

      {/* Cloud Sync Dashboard Modal (rendered inside React Portal for viewport centering) */}
      {showSyncModal && mounted && typeof document !== "undefined"
        ? createPortal(
            <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
              <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-md" onClick={() => setShowSyncModal(false)} />
              
              <div className="relative w-full max-w-md glass-panel p-6 rounded-2xl border border-white/10 bg-slate-900 shadow-2xl z-10 space-y-6 animate-in fade-in duration-200">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-white/5 pb-3">
                  <div className="flex items-center gap-2">
                    <svg className="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                    <h3 className="text-md font-bold text-slate-200">{t.driveSync.title}</h3>
                  </div>
                  <button onClick={() => setShowSyncModal(false)} className="text-slate-500 hover:text-white p-1 rounded hover:bg-white/5">
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>

                {/* Sync Info */}
                <div className="space-y-4 text-sm text-slate-300">
                  <div className="flex justify-between items-center bg-slate-950/40 p-3 rounded-xl border border-white/5">
                    <div>
                      <span className="text-[10px] text-slate-500 uppercase font-black tracking-wider block">{t.driveSync.linkedAccount}</span>
                      <span className="font-extrabold text-slate-300 truncate block max-w-[200px]">{user?.email}</span>
                    </div>
                    <div>
                      <span className="text-[10px] text-slate-500 uppercase font-black tracking-wider block text-right">{t.driveSync.status}</span>
                      <span className={`font-bold text-xs uppercase ${
                        syncStatus === "synced" ? "text-emerald-400" :
                        syncStatus === "pending" ? "text-amber-400 animate-pulse" :
                        syncStatus === "syncing" ? "text-cyan-400" : "text-rose-400"
                      }`}>
                        {syncStatus === "synced" ? t.driveSync.statusSynced :
                         syncStatus === "pending" ? t.driveSync.statusPending :
                         syncStatus === "syncing" ? t.driveSync.statusSyncing : t.driveSync.statusError}
                      </span>
                    </div>
                  </div>

                  <div className="text-xs text-slate-400 space-y-1">
                    <div>📅 **{t.driveSync.lastBackup}**: {lastSyncDate || t.driveSync.never}</div>
                    <div>💾 **{t.driveSync.fileLabel}**: `slotrace_backup.json`</div>
                    <div>🔒 **{t.driveSync.securityLabel}**</div>
                    {accessToken ? (
                      <div className="text-emerald-400 font-medium mt-1">{t.driveSync.connectedSuccess}</div>
                    ) : (
                      <div className="text-slate-500 italic mt-1">{t.driveSync.connectWarning}</div>
                    )}
                  </div>
                </div>

                {/* Actions */}
                <div className="grid grid-cols-2 gap-3 pt-2">
                  <button
                    type="button"
                    onClick={handleBackup}
                    disabled={syncStatus === "syncing"}
                    className="w-full py-3 rounded-xl text-xs font-black uppercase tracking-wider bg-cyan-500 hover:bg-cyan-400 text-black shadow-lg hover:shadow-cyan-500/10 transition-all disabled:opacity-50 active:scale-[0.98]"
                  >
                    {t.driveSync.btnSave}
                  </button>
                  <button
                    type="button"
                    onClick={handleRestore}
                    disabled={syncStatus === "syncing"}
                    className="w-full py-3 rounded-xl text-xs font-black uppercase tracking-wider bg-white/5 hover:bg-white/10 text-slate-200 border border-white/10 hover:border-white/20 transition-all disabled:opacity-50 active:scale-[0.98]"
                  >
                    {t.driveSync.btnRestore}
                  </button>
                </div>

                {/* Cloud Logs Terminal */}
                <div className="space-y-1.5 border-t border-white/5 pt-4">
                  <span className="text-[9px] text-slate-500 font-bold uppercase tracking-wider block">{t.driveSync.terminalTitle}</span>
                  <div className="w-full h-32 p-3 bg-slate-950 rounded-xl font-mono-telemetry text-[10px] text-slate-400 space-y-1 overflow-y-auto border border-white/5 select-text scrollbar-thin">
                    {syncLogs.length === 0 ? (
                      <div className="text-slate-600 italic">{t.driveSync.noLogs}</div>
                    ) : (
                      syncLogs.map((log, i) => <div key={i}>{log}</div>)
                    )}
                  </div>
                </div>

              </div>
            </div>,
            document.body
          )
        : null}
    </>
  );
}

