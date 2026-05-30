"use client";

import { useState, useEffect, useRef } from "react";
import { useAuth } from "../context/AuthContext";
import { useTranslation } from "@/context/LanguageContext";

export default function LoginScreen() {
  const { login } = useAuth();
  const { t, lang } = useTranslation();
  
  // Simulated Chooser state
  const [showSimulator, setShowSimulator] = useState(false);
  const [customName, setCustomName] = useState("");
  const [customEmail, setCustomEmail] = useState("");
  const [customAvatar, setCustomAvatar] = useState("https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80"); // Preset high-quality avatar
  const [loadingRealGoogle, setLoadingRealGoogle] = useState(false);
  const [gsiLoaded, setGsiLoaded] = useState(false);

  const googleBtnRef = useRef(null);

  const presetAccounts = [
    {
      name: "Ayrton Senna",
      email: "senna@slotrace.com",
      avatar_url: "https://images.unsplash.com/photo-1560250097-0b93528c311a?w=150&auto=format&fit=crop&q=80",
    },
    {
      name: "Bruno Bozz",
      email: "brunobozz@gmail.com",
      avatar_url: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80",
    },
    {
      name: "Felipe Massa",
      email: "massa@slotrace.com",
      avatar_url: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80",
    }
  ];

  // Try loading real Google Identity Services script
  useEffect(() => {
    const clientId = process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID;
    if (!clientId) {
      console.log("NEXT_PUBLIC_GOOGLE_CLIENT_ID variable not configured. Activating simulator mode.");
      return;
    }

    // Load GSI script
    const script = document.createElement("script");
    script.src = "https://accounts.google.com/gsi/client";
    script.async = true;
    script.defer = true;
    script.onload = () => {
      setGsiLoaded(true);
      try {
        window.google?.accounts.id.initialize({
          client_id: clientId,
          callback: handleGoogleCredentialResponse,
        });
      } catch (err) {
        console.error("Error initializing GSI:", err);
      }
    };
    document.body.appendChild(script);

    return () => {
      document.body.removeChild(script);
    };
  }, []);

  // Dynamically render real Google button if script is loaded and client ID exists
  useEffect(() => {
    const clientId = process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID;
    if (gsiLoaded && clientId) {
      try {
        setTimeout(() => {
          const container = document.getElementById("google-signin-button");
          if (container) {
            window.google?.accounts.id.renderButton(
              container,
              { 
                theme: "filled_black", 
                size: "large", 
                width: "320", 
                shape: "pill",
                text: "signin_with" 
              }
            );
          }
        }, 100);
      } catch (err) {
        console.error("Error rendering official Google button:", err);
      }
    }
  }, [gsiLoaded]);

  // Handle Google OAuth callback
  const handleGoogleCredentialResponse = (response) => {
    try {
      setLoadingRealGoogle(true);
      // Decode JWT token payload (client side)
      const base64Url = response.credential.split(".")[1];
      const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
      const jsonPayload = decodeURIComponent(
        atob(base64)
          .split("")
          .map((c) => "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2))
          .join("")
      );
      
      const payload = JSON.parse(jsonPayload);
      
      login({
        name: payload.name || "Google User",
        email: payload.email,
        avatar_url: payload.picture || "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80",
        token: response.credential,
        auth_type: "google_oauth"
      });
    } catch (err) {
      console.error("Error processing real Google login:", err);
      alert("Failed official Google login. Using local simulator.");
      setShowSimulator(true);
    } finally {
      setLoadingRealGoogle(false);
    }
  };

  const triggerGoogleLogin = () => {
    const clientId = process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID;
    if (gsiLoaded && clientId) {
      try {
        window.google?.accounts.id.prompt(); // Trigger One Tap if available
        // Or render and click hidden gsi button
        window.google?.accounts.id.requestAccessToken();
      } catch (err) {
        console.log("Google prompt failed, triggering simulator:", err);
        setShowSimulator(true);
      }
    } else {
      setShowSimulator(true);
    }
  };

  const handleSimulatedLogin = (account) => {
    login({
      name: account.name,
      email: account.email,
      avatar_url: account.avatar_url,
      auth_type: "google_simulated"
    });
  };

  const handleCustomSimulatedLogin = (e) => {
    e.preventDefault();
    if (!customName.trim() || !customEmail.trim()) return;
    
    login({
      name: customName,
      email: customEmail,
      avatar_url: customAvatar,
      auth_type: "google_simulated_custom"
    });
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-[#05070c] overflow-hidden font-outfit select-none">
      
      {/* 1. Cyberpunk Premium Backdrop */}
      {/* Animated Matrix Cyber Grid lines */}
      <div className="absolute inset-0 bg-[linear-gradient(to_right,#1e293b08_1px,transparent_1px),linear-gradient(to_bottom,#1e293b08_1px,transparent_1px)] bg-[size:4rem_4rem]" />
      
      {/* Glowing Dynamic Ambient Orbs */}
      <div className="absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-500/10 rounded-full blur-[100px] animate-pulse duration-10000" />
      <div className="absolute bottom-1/4 right-1/4 translate-x-1/2 translate-y-1/2 w-96 h-96 bg-rose-500/10 rounded-full blur-[100px] animate-pulse duration-7000" />
      
      {/* Diagnostics / Scanner line */}
      <div className="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent shadow-[0_0_15px_rgba(6,182,212,0.3)] animate-scan" />

      {/* Background HUD Telemetry Details */}
      <div className="absolute top-6 left-6 font-mono-telemetry text-[9px] text-slate-600 space-y-0.5 tracking-wider hidden sm:block">
        <div>SYS: SLOTRACE.OAUTH.CLIENT</div>
        <div>SECURE CHANNEL: PROT.256.GSI</div>
        <div>IP: 127.0.0.1 // DEV_ENV</div>
      </div>
      
      <div className="absolute bottom-6 right-6 font-mono-telemetry text-[9px] text-slate-600 space-y-0.5 tracking-wider hidden sm:block">
        <div>STATUS: PENDING_CREDENTIAL</div>
        <div>LATENCY: 4.12ms</div>
        <div>GRID NETWORK: SYNCED</div>
      </div>

      {/* 2. Glassmorphic Login Card */}
      <div className="relative w-full max-w-md mx-4 glass-panel p-8 rounded-3xl border border-white/10 bg-slate-950/80 shadow-3xl text-center space-y-8 z-10">
        
        {/* Glow behind card */}
        <div className="absolute -inset-0.5 bg-gradient-to-r from-cyan-500/20 to-rose-500/20 rounded-3xl blur opacity-30 group-hover:opacity-100 transition duration-1000 -z-10" />

        {/* System Emblem Logo */}
        <div className="flex flex-col items-center gap-3">
          <div className="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-rose-500 flex items-center justify-center shadow-xl shadow-cyan-500/20 animate-bounce duration-5000">
            <svg className="w-9 h-9 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2.5">
              <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>
          </div>
          
          <div className="space-y-1">
            <h1 className="text-2xl font-black tracking-widest bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-rose-400">
              SLOTRACE
            </h1>
            <p className="text-[10px] text-slate-500 font-extrabold tracking-widest uppercase">
              {t.login.tagline}
            </p>
          </div>
        </div>

        {/* Division Line */}
        <div className="w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent" />

        {/* Message and Instruction */}
        <div className="space-y-2">
          <h2 className="text-base font-bold text-slate-200">{t.login.restrictedAccess}</h2>
          <p className="text-xs text-slate-400 max-w-xs mx-auto leading-relaxed">
            To access the telemetry dashboard, lap times and championships, you must connect using your Google Account.
          </p>
        </div>

        {/* Premium Action Button (Google Sign In) */}
        <div className="space-y-4">
          {process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID && gsiLoaded ? (
            <div className="flex flex-col gap-3">
              {/* Native Google OAuth Button */}
              <div className="flex justify-center p-1.5 bg-slate-900/40 border border-white/5 rounded-2xl py-3 px-4 relative group hover:border-cyan-500/30 transition-all duration-300">
                <div id="google-signin-button" className="min-h-[40px]"></div>
                <span className="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gradient-to-r from-cyan-400 to-rose-400 group-hover:w-[80%] transition-all duration-500" />
              </div>
              <button
                type="button"
                onClick={() => setShowSimulator(true)}
                className="w-full py-2.5 rounded-xl border border-dashed border-white/10 hover:border-cyan-500/30 bg-slate-950/40 hover:bg-slate-950 text-slate-400 hover:text-slate-200 text-[10px] uppercase font-bold tracking-widest transition-all active:scale-[0.98]"
              >
                {t.login.btnSimulator}
              </button>
            </div>
          ) : (
            /* Custom button triggering simulator directly */
            <button
              type="button"
              onClick={() => setShowSimulator(true)}
              className="w-full flex items-center justify-center gap-4 py-3.5 px-6 rounded-2xl border border-white/10 bg-slate-900/60 hover:bg-slate-900/90 text-slate-200 hover:text-white font-bold text-sm shadow-lg hover:shadow-cyan-500/10 hover:border-cyan-500/30 transition-all duration-300 relative group active:scale-[0.98]"
            >
              {/* Google Colorful G Logo */}
              <svg className="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                  fill="#4285F4"
                />
                <path
                  d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                  fill="#34A853"
                />
                <path
                  d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"
                  fill="#FBBC05"
                />
                <path
                  d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"
                  fill="#EA4335"
                />
              </svg>
              <span>{t.login.btnAccSimulator}</span>
              <span className="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gradient-to-r from-cyan-400 to-rose-400 group-hover:w-[80%] transition-all duration-500" />
            </button>
          )}
          
          <div className="text-[9px] font-mono-telemetry text-slate-500 flex items-center justify-center gap-1.5 uppercase">
            <span className="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-ping" />
            Active SSL encryption channel
          </div>
        </div>

        {/* Footer info inside Card */}
        <div className="text-[10px] text-slate-600 font-semibold tracking-wider uppercase pt-2">
          {gsiLoaded ? "Google OAuth Service Active" : "Local Simulator Activated"}
        </div>
      </div>

      {/* 3. Simulated Google Account Chooser Modal (Developer Fallback) */}
      {showSimulator && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          {/* Backdrop blur */}
          <div 
            className="fixed inset-0 bg-slate-950/70 backdrop-blur-md"
            onClick={() => setShowSimulator(false)}
          />

          {/* Dialog */}
          <div className="relative w-full max-w-sm glass-panel p-6 rounded-2xl border border-white/10 bg-slate-900 shadow-2xl z-10 space-y-6">
            
            {/* Simulated Google Header */}
            <div className="flex flex-col items-center gap-2 text-center">
              <svg className="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
              </svg>
              <h3 className="text-md font-bold text-slate-200">{t.login.chooserTitle}</h3>
              <p className="text-xs text-slate-400">{t.login.chooserSub}</p>
            </div>

            {/* List of preset profiles */}
            <div className="space-y-2">
              {presetAccounts.map((account) => (
                <button
                  key={account.email}
                  type="button"
                  onClick={() => handleSimulatedLogin(account)}
                  className="w-full flex items-center justify-between p-3 rounded-xl border border-white/5 bg-slate-955 hover:bg-slate-950 text-slate-300 hover:text-white text-left transition-all active:scale-[0.98] group"
                >
                  <div className="flex items-center gap-3">
                    <img 
                      src={account.avatar_url} 
                      alt={account.name} 
                      className="w-8 h-8 rounded-full object-cover border border-white/10"
                    />
                    <div className="flex flex-col">
                      <span className="text-xs font-bold">{account.name}</span>
                      <span className="text-[10px] text-slate-500 font-mono-telemetry">{account.email}</span>
                    </div>
                  </div>
                  <span className="text-[10px] text-cyan-500 font-bold bg-cyan-500/10 px-2 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                    {t.login.btnConnect}
                  </span>
                </button>
              ))}
            </div>

            <div className="flex items-center my-3 text-slate-600 text-[10px] font-bold tracking-widest uppercase">
              <div className="flex-1 h-px bg-white/5" />
              <span className="px-2">{t.login.customAccount}</span>
              <div className="flex-1 h-px bg-white/5" />
            </div>

            {/* Custom account inputs */}
            <form onSubmit={handleCustomSimulatedLogin} className="space-y-3">
              <div className="space-y-1">
                <input
                  type="text"
                  required
                  placeholder={t.login.placeholderName}
                  value={customName}
                  onChange={(e) => setCustomName(e.target.value)}
                  className="w-full px-3 py-2 rounded-xl bg-slate-955 border border-white/5 text-slate-200 text-xs focus:outline-none focus:border-cyan-500/50 transition-colors"
                />
              </div>
              <div className="space-y-1">
                <input
                  type="email"
                  required
                  placeholder={t.login.placeholderEmail}
                  value={customEmail}
                  onChange={(e) => setCustomEmail(e.target.value)}
                  className="w-full px-3 py-2 rounded-xl bg-slate-955 border border-white/5 text-slate-200 text-xs focus:outline-none focus:border-cyan-500/50 transition-colors"
                />
              </div>

              {/* Avatar Selector */}
              <div className="space-y-1.5">
                <label className="text-[9px] text-slate-500 font-bold uppercase tracking-wider block">Avatar Style</label>
                <div className="grid grid-cols-4 gap-2">
                  {[
                    { url: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80", label: "A" },
                    { url: "https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=150&auto=format&fit=crop&q=80", label: "B" },
                    { url: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80", label: "C" },
                    { url: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80", label: "D" },
                  ].map((av) => (
                    <button
                      key={av.url}
                      type="button"
                      onClick={() => setCustomAvatar(av.url)}
                      className={`relative rounded-xl overflow-hidden aspect-square border-2 transition-all ${customAvatar === av.url ? 'border-cyan-500 scale-95 shadow-md shadow-cyan-500/20' : 'border-white/5 hover:border-white/20'}`}
                    >
                      <img src={av.url} alt="Option" className="w-full h-full object-cover" />
                    </button>
                  ))}
                </div>
              </div>

              <div className="flex gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setShowSimulator(false)}
                  className="flex-1 py-2.5 rounded-xl text-[10px] font-extrabold bg-white/5 hover:bg-white/10 text-slate-400 uppercase tracking-wider transition-all"
                >
                  {t.login.btnBack}
                </button>
                <button
                  type="submit"
                  className="flex-1 py-2.5 rounded-xl text-[10px] font-extrabold bg-cyan-500 hover:bg-cyan-400 text-black uppercase tracking-wider transition-all"
                >
                  {t.login.btnSignIn}
                </button>
              </div>
            </form>

          </div>
        </div>
      )}

    </div>
  );
}
