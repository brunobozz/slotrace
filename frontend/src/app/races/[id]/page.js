"use client";

import { useEffect, useState, useRef } from "react";
import { useParams, useRouter } from "next/navigation";
import { api } from "@/services/api";
import { useTranslation } from "@/context/LanguageContext";

export default function RacePanel() {
  const { id } = useParams();
  const router = useRouter();
  const { t, lang } = useTranslation();

  // Mode state: is it a local active race in memory?
  const isLocalActive = typeof id === "string" && id.startsWith("local_");

  const [raceData, setRaceData] = useState(null);
  const [laps, setLaps] = useState([]); // All recorded laps in memory (local active mode)
  const [leaderboard, setLeaderboard] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);
  
  // Simulation config
  const [simMinTime, setSimMinTime] = useState(4.8);
  const [simMaxTime, setSimMaxTime] = useState(5.8);
  const [newRecordAlert, setNewRecordAlert] = useState(false);
  const [recordDriver, setRecordDriver] = useState("");

  const pollingInterval = useRef(null);

  // Helper: Calculate leaderboard standings locally
  const calculateLocalLeaderboard = (participants, recordedLaps) => {
    const standings = participants.map((part) => {
      const driverLaps = recordedLaps.filter(l => l.driver_id === part.driver_id)
                                      .sort((a, b) => a.lap_number - b.lap_number);
                                      
      const lapsCompleted = driverLaps.length;
      const bestLap = lapsCompleted > 0 ? Math.min(...driverLaps.map(l => l.lap_time_seconds)) : null;
      const totalTime = driverLaps.reduce((acc, l) => acc + l.lap_time_seconds, 0);
      const lastLap = lapsCompleted > 0 ? driverLaps[lapsCompleted - 1].lap_time_seconds : null;

      return {
        driver_id: part.driver_id,
        driver_name: part.driver?.name || t.driver.driver,
        driver_nickname: part.driver?.nickname || "",
        driver_avatar_url: part.driver?.avatar_url || null,
        car_name: part.car?.name || t.car.car,
        lane_number: part.lane_number,
        status: part.status,
        laps_completed: lapsCompleted,
        best_lap: bestLap,
        total_time: totalTime,
        last_lap: lastLap,
        laps: driverLaps.map(l => ({
          lap_number: l.lap_number,
          lap_time_seconds: l.lap_time_seconds
        }))
      };
    });

    // Standings sorting logic matching backend:
    // 1. Most laps completed (desc)
    // 2. Lowest total time (asc)
    // 3. Lane number (asc)
    standings.sort((a, b) => {
      if (a.laps_completed !== b.laps_completed) {
        return b.laps_completed - a.laps_completed;
      }
      if (a.total_time !== b.total_time) {
        return a.total_time - b.total_time;
      }
      return a.lane_number - b.lane_number;
    });

    return standings.map((item, idx) => ({
      ...item,
      position: idx + 1
    }));
  };

  // Load race data initially
  useEffect(() => {
    const initRace = async () => {
      try {
        setLoading(true);
        if (isLocalActive) {
          // Local Active Mode: Load from localStorage config
          const configStr = localStorage.getItem(`local_race_${id}`);
          if (!configStr) {
            setError("Local race configuration not found.");
            setLoading(false);
            return;
          }
          
          const config = JSON.parse(configStr);
          
          // Fetch master data to fill details
          const [trackDetails, driversList, carsList] = await Promise.all([
            api.tracks.show(config.track_id),
            api.drivers.list(),
            api.cars.list()
          ]);

          const localRaceObj = {
            id: id,
            name: config.name,
            track_id: config.track_id,
            track: trackDetails,
            type: config.type,
            laps_limit: config.laps_limit,
            duration_seconds: config.duration_seconds,
            status: "pending",
            participants: config.participants.map(p => ({
              ...p,
              driver: driversList.find(d => d.id === p.driver_id),
              car: carsList.find(c => c.id === p.car_id),
              status: "ready"
            }))
          };

          setRaceData(localRaceObj);
          setLaps([]);
          setLeaderboard(calculateLocalLeaderboard(localRaceObj.participants, []));
        } else {
          // Server view mode: Load finished race from database
          const [raceDetails, leaderboardData] = await Promise.all([
            api.races.show(id),
            api.races.leaderboard(id),
          ]);
          setRaceData(raceDetails);
          setLeaderboard(leaderboardData.leaderboard || []);
        }
        setError(null);
      } catch (err) {
        console.error(err);
        setError("Error loading race data.");
      } finally {
        setLoading(false);
      }
    };

    initRace();
  }, [id]);

  // Handler: Start local active race
  const handleStartRace = () => {
    if (!isLocalActive) return;
    setRaceData(prev => {
      const updatedParts = prev.participants.map(p => ({ ...p, status: "racing" }));
      return { ...prev, status: "in_progress", participants: updatedParts };
    });
  };

  // Handler: Pause local active race
  const handlePauseRace = () => {
    if (!isLocalActive) return;
    setRaceData(prev => {
      const updatedParts = prev.participants.map(p => (p.status === "racing" ? { ...p, status: "paused" } : p));
      return { ...prev, status: "paused", participants: updatedParts };
    });
  };

  // Handler: Resume local active race
  const handleResumeRace = () => {
    if (!isLocalActive) return;
    setRaceData(prev => {
      const updatedParts = prev.participants.map(p => (p.status === "paused" ? { ...p, status: "racing" } : p));
      return { ...prev, status: "in_progress", participants: updatedParts };
    });
  };

  // Handler: Finish local active race manually
  const handleFinishRace = () => {
    if (!isLocalActive) return;
    if (!confirm("Do you really want to end the race and consolidate the times?")) return;
    
    setRaceData(prev => {
      const updatedParts = prev.participants.map(p => (p.status === "racing" || p.status === "paused" ? { ...p, status: "finished" } : p));
      return { ...prev, status: "finished", participants: updatedParts };
    });
  };

  // Handler: Simulate lap triggered locally (Offline-First)
  const handleSimulateLap = (laneNumber, driverName) => {
    if (!isLocalActive) return;
    if (raceData.status !== "in_progress") {
      alert("The race must be IN PROGRESS to record lap times!");
      return;
    }

    const participant = raceData.participants.find(p => p.lane_number === laneNumber);
    if (!participant || participant.status === "finished") {
      return;
    }

    // Generate random lap time between bounds
    const randomTime = parseFloat((Math.random() * (parseFloat(simMaxTime) - parseFloat(simMinTime)) + parseFloat(simMinTime)).toFixed(3));
    
    // Count current laps of the driver to get new lap number
    const driverLapsCount = laps.filter(l => l.driver_id === participant.driver_id).length;
    const newLapNumber = driverLapsCount + 1;

    const newLap = {
      driver_id: participant.driver_id,
      lane_number: participant.lane_number,
      lap_number: newLapNumber,
      lap_time_seconds: randomTime
    };

    const updatedLaps = [...laps, newLap];
    setLaps(updatedLaps);

    // Check track record locally
    const currentRecord = raceData.track?.best_lap_time;
    if (empty(currentRecord) || randomTime < currentRecord) {
      setRecordDriver(driverName);
      setNewRecordAlert(true);
      setTimeout(() => setNewRecordAlert(false), 4000);
      
      // Update record in our local track details state
      setRaceData(prev => ({
        ...prev,
        track: {
          ...prev.track,
          best_lap_time: randomTime,
          best_lap_driver_id: participant.driver_id
        }
      }));
    }

    // Handle lap limits automatic finish
    let updatedParticipantStatus = participant.status;
    if (raceData.type === "lap_race" && raceData.laps_limit && newLapNumber >= raceData.laps_limit) {
      updatedParticipantStatus = "finished";
    }

    // Update participant status in our state list
    const updatedParticipants = raceData.participants.map(p => 
      p.lane_number === laneNumber ? { ...p, status: updatedParticipantStatus } : p
    );

    // If all participants finished, mark the race itself as finished
    const racingCount = updatedParticipants.filter(p => p.status === "racing" || p.status === "paused").length;
    const newRaceStatus = racingCount === 0 ? "finished" : raceData.status;

    setRaceData(prev => ({
      ...prev,
      status: newRaceStatus,
      participants: updatedParticipants
    }));

    // Update leaderboard immediately
    setLeaderboard(calculateLocalLeaderboard(updatedParticipants, updatedLaps));
  };

  // Helper check for empty database values
  const empty = (val) => val === undefined || val === null || val === "";

  // Handler: Bulk save completed race GP to the server
  const handleSaveToServer = async () => {
    if (!isLocalActive) return;
    try {
      setSaving(true);
      
      const payload = {
        name: raceData.name,
        track_id: raceData.track_id,
        type: raceData.type,
        laps_limit: raceData.laps_limit,
        duration_seconds: raceData.duration_seconds,
        participants: raceData.participants.map(p => ({
          driver_id: p.driver_id,
          car_id: p.car_id,
          lane_number: p.lane_number
        })),
        laps: laps.map(l => ({
          driver_id: l.driver_id,
          lane_number: l.lane_number,
          lap_number: l.lap_number,
          lap_time_seconds: l.lap_time_seconds
        }))
      };

      await api.races.saveCompleted(payload);
      
      // Clean config in localStorage upon success
      localStorage.removeItem(`local_race_${id}`);
      
      alert("Grand Prix successfully saved and consolidated on the server!");
      router.push("/");
    } catch (err) {
      console.error(err);
      alert("Error saving race on the server: " + err.message);
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[50vh] gap-4">
        <div className="w-12 h-12 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
        <span className="text-slate-400 font-semibold tracking-wider">{t.telemetry.receivers}</span>
      </div>
    );
  }

  if (!raceData) {
    return (
      <div className="text-center py-12 glass-panel rounded-3xl max-w-lg mx-auto">
        <p className="text-slate-400">{t.telemetry.notFound}</p>
        <button onClick={() => router.push("/")} className="mt-4 px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white font-bold text-sm">
          {t.telemetry.btnDashboard}
        </button>
      </div>
    );
  }

  // Visual settings for lanes colors
  const laneBadgeColors = [
    "bg-cyan-500/10 text-cyan-400 border-cyan-500/20",
    "bg-rose-500/10 text-rose-400 border-rose-500/20",
    "bg-emerald-500/10 text-emerald-400 border-emerald-500/20",
    "bg-yellow-500/10 text-yellow-400 border-yellow-500/20"
  ];
  const laneGlowClasses = [
    "border-t-4 border-t-cyan-400 shadow-md shadow-cyan-400/5",
    "border-t-4 border-t-rose-500 shadow-md shadow-rose-500/5",
    "border-t-4 border-t-emerald-500 shadow-md shadow-emerald-500/5",
    "border-t-4 border-t-yellow-400 shadow-md shadow-yellow-400/5"
  ];
  const laneButtonGlow = [
    "bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border-cyan-500/20 active:scale-95",
    "bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border-rose-500/20 active:scale-95",
    "bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border-emerald-500/20 active:scale-95",
    "bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400 border-yellow-500/20 active:scale-95"
  ];

  return (
    <div className="space-y-8 animate-in fade-in duration-300">
      
      {/* Track Record Alert Overlay */}
      {newRecordAlert && (
        <div className="fixed top-20 right-4 z-50 p-5 rounded-2xl border border-rose-500/40 bg-slate-950/90 shadow-2xl shadow-rose-500/20 animate-bounce flex items-center gap-4 max-w-sm">
          <div className="w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center text-black text-lg">🏆</div>
          <div>
            <h4 className="font-extrabold text-rose-400 text-sm tracking-wide uppercase">{t.telemetry.bestLapAlert}</h4>
            <p className="text-xs text-slate-300 font-semibold">{recordDriver} {t.telemetry.beatRecordMsg}</p>
          </div>
        </div>
      )}

      {/* Header Info Panel */}
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-gradient-to-b from-slate-900 to-slate-950 border border-white/5 p-6 sm:p-8 rounded-3xl">
        <div className="space-y-3">
          <div className="flex flex-wrap items-center gap-3">
            <span className="text-[10px] tracking-widest font-black uppercase px-2.5 py-0.5 rounded border border-white/10 bg-white/5 text-slate-400">
              {t.track.track}: {raceData.track?.name}
            </span>
            <span className="text-[10px] tracking-widest font-black uppercase px-2.5 py-0.5 rounded border border-cyan-500/20 bg-cyan-500/5 text-cyan-400">
              {raceData.type === "lap_race" ? `${t.createRace.modeLapLimit} (${raceData.laps_limit} ${t.driver.laps})` : t.createRace.modeTimeTrial}
            </span>
            {isLocalActive && (
              <span className="text-[10px] tracking-widest font-black uppercase px-2.5 py-0.5 rounded border border-purple-500/20 bg-purple-500/5 text-purple-400">
                {t.telemetry.offlineMode}
              </span>
            )}
          </div>
          <h1 className="text-3xl font-extrabold text-white tracking-tight">{raceData.name}</h1>
          <p className="text-slate-400 text-xs font-semibold">
            {t.telemetry.status}: {" "}
            {raceData.status === "finished" ? (
              <span className="text-rose-400 uppercase font-black tracking-widest">{t.telemetry.statusFinished}</span>
            ) : raceData.status === "in_progress" ? (
              <span className="text-emerald-400 uppercase font-black tracking-widest animate-pulse">{t.telemetry.statusInProgress}</span>
            ) : raceData.status === "paused" ? (
              <span className="text-yellow-400 uppercase font-black tracking-widest">{t.telemetry.statusPaused}</span>
            ) : (
              <span className="text-amber-400 uppercase font-black tracking-widest">{t.telemetry.statusPending}</span>
            )}
          </p>
        </div>

        {/* Action Controls */}
        <div className="flex items-center gap-3">
          {/* Active local race controls */}
          {isLocalActive && (
            <>
              {raceData.status === "pending" && (
                <button
                  onClick={handleStartRace}
                  className="px-6 py-3.5 rounded-2xl text-sm font-extrabold bg-gradient-to-r from-emerald-400 to-teal-500 hover:from-emerald-300 hover:to-teal-400 text-black shadow-xl shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all flex items-center gap-2"
                >
                  <svg className="w-5 h-5 fill-black" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                  </svg>
                  {t.telemetry.btnStart}
                </button>
              )}

              {raceData.status === "in_progress" && (
                <>
                  <button
                    onClick={handlePauseRace}
                    className="px-6 py-3.5 rounded-2xl text-sm font-extrabold bg-amber-500 hover:bg-amber-400 text-black shadow-xl shadow-amber-500/10 hover:shadow-amber-500/20 transition-all flex items-center gap-2"
                  >
                    <svg className="w-5 h-5 fill-black" viewBox="0 0 24 24">
                      <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                    </svg>
                    {t.telemetry.btnPause}
                  </button>
                  <button
                    onClick={handleFinishRace}
                    className="px-6 py-3.5 rounded-2xl text-sm font-extrabold bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white shadow-xl shadow-rose-500/10 hover:shadow-rose-500/20 transition-all flex items-center gap-2"
                  >
                    <svg className="w-5 h-5 fill-white" viewBox="0 0 24 24">
                      <path d="M6 6h12v12H6z" />
                    </svg>
                    {t.telemetry.btnFinish}
                  </button>
                </>
              )}

              {raceData.status === "paused" && (
                <>
                  <button
                    onClick={handleResumeRace}
                    className="px-6 py-3.5 rounded-2xl text-sm font-extrabold bg-emerald-500 hover:bg-emerald-400 text-black shadow-xl shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all flex items-center gap-2"
                  >
                    <svg className="w-5 h-5 fill-black" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                    {t.telemetry.btnResume}
                  </button>
                  <button
                    onClick={handleFinishRace}
                    className="px-6 py-3.5 rounded-2xl text-sm font-extrabold bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white shadow-xl shadow-rose-500/10 hover:shadow-rose-500/20 transition-all flex items-center gap-2"
                  >
                    <svg className="w-5 h-5 fill-white" viewBox="0 0 24 24">
                      <path d="M6 6h12v12H6z" />
                    </svg>
                    {t.telemetry.btnFinish}
                  </button>
                </>
              )}

              {raceData.status === "finished" && (
                <button
                  onClick={handleSaveToServer}
                  disabled={saving}
                  className="px-7 py-3.5 rounded-2xl text-sm font-black bg-gradient-to-r from-cyan-400 to-blue-500 hover:from-cyan-300 hover:to-blue-400 text-black shadow-xl shadow-cyan-500/20 hover:shadow-cyan-500/30 transition-all flex items-center gap-2"
                >
                  <svg className="w-5 h-5 fill-black" viewBox="0 0 24 24">
                    <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z" />
                  </svg>
                  {saving ? t.telemetry.savingCloud : t.telemetry.btnSaveCloud}
                </button>
              )}
            </>
          )}

          {/* Read-only completed server race */}
          {!isLocalActive && (
            <button
              onClick={() => router.push("/races/create")}
              className="px-6 py-3.5 rounded-2xl text-sm font-extrabold bg-white/5 hover:bg-white/10 text-white transition-all"
            >
              {t.telemetry.btnNewGp}
            </button>
          )}
        </div>
      </div>

      {/* Grid: Simulator & Leaderboard */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {/* Left/Simulators (1/3) */}
        <div className="space-y-6 lg:col-span-1">
          <div className="space-y-2">
            <h2 className="text-lg font-bold text-slate-200 uppercase tracking-tight">
              {isLocalActive ? t.telemetry.simPanel : t.telemetry.trackData}
            </h2>
            <p className="text-xs text-slate-500">
              {isLocalActive 
                ? t.telemetry.simSub
                : t.telemetry.simSubServer}
            </p>
          </div>

          {/* Simulator Settings (Only if local and active) */}
          {isLocalActive && raceData.status !== "finished" && (
            <div className="glass-panel p-5 rounded-2xl space-y-4">
              <h3 className="text-xs font-bold text-slate-400 uppercase tracking-wider">{t.telemetry.simPerfTitle}</h3>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-1">
                  <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">{t.telemetry.simMin}</label>
                  <input
                    type="number"
                    step="0.1"
                    value={simMinTime}
                    onChange={(e) => setSimMinTime(e.target.value)}
                    className="w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/5 text-slate-200 text-xs font-mono-telemetry font-bold focus:outline-none"
                  />
                </div>
                <div className="space-y-1">
                  <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">{t.telemetry.simMax}</label>
                  <input
                    type="number"
                    step="0.1"
                    value={simMaxTime}
                    onChange={(e) => setSimMaxTime(e.target.value)}
                    className="w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/5 text-slate-200 text-xs font-mono-telemetry font-bold focus:outline-none"
                  />
                </div>
              </div>
            </div>
          )}

          {/* Drivers Slots display */}
          <div className="space-y-4">
            {raceData.participants?.map((part) => {
              const colorIdx = (part.lane_number - 1) % 4;
              return (
                <div key={part.driver_id} className={`glass-panel p-5 rounded-2xl space-y-4 ${laneGlowClasses[colorIdx]}`}>
                  <div className="flex items-center justify-between">
                    <div>
                      <span className={`text-[10px] uppercase font-black px-2 py-0.5 rounded border ${laneBadgeColors[colorIdx]}`}>
                        {t.telemetry.lane} {part.lane_number}
                      </span>
                      <h4 className="font-extrabold text-slate-200 mt-2 text-base">{part.driver?.name}</h4>
                      <p className="text-xs text-slate-400 mt-0.5">{part.car?.name}</p>
                    </div>
                    <div className="text-right">
                      <span className="text-[10px] text-slate-500 font-bold uppercase tracking-wider">{t.telemetry.status}</span>
                      <p className={`text-xs font-bold uppercase mt-0.5 ${
                        part.status === "finished" 
                          ? "text-rose-400" 
                          : raceData.status === "in_progress" && part.status !== "finished"
                          ? "text-emerald-400 animate-pulse" 
                          : raceData.status === "paused"
                          ? "text-yellow-400 animate-pulse" 
                          : "text-amber-400"
                      }`}>
                        {part.status === "finished" ? t.telemetry.statusFinishedBadge : raceData.status === "in_progress" ? t.telemetry.statusRacingBadge : raceData.status === "paused" ? t.telemetry.statusPausedBadge : t.telemetry.statusReadyBadge}
                      </p>
                    </div>
                  </div>

                  {isLocalActive && raceData.status !== "finished" && (
                    <button
                      onClick={() => handleSimulateLap(part.lane_number, part.driver?.nickname || part.driver?.name)}
                      disabled={raceData.status !== "in_progress" || part.status === "finished"}
                      className={`w-full py-3 rounded-xl border text-xs font-black uppercase tracking-wider transition-all duration-200 disabled:opacity-40 disabled:pointer-events-none ${laneButtonGlow[colorIdx]}`}
                    >
                      {t.telemetry.triggerBtn}
                    </button>
                  )}
                </div>
              );
            })}
          </div>
        </div>

        {/* Right/Leaderboard (2/3) */}
        <div className="space-y-6 lg:col-span-2">
          <div className="flex items-center justify-between">
            <h2 className="text-lg font-bold text-slate-200 uppercase tracking-tight">
              {t.telemetry.standingsTitle}
            </h2>
            {isLocalActive && raceData.status === "in_progress" && (
              <span className="flex items-center gap-1.5 text-xs text-emerald-400 font-bold tracking-wider">
                <span className="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                {t.telemetry.liveUpdate}
              </span>
            )}
            {isLocalActive && raceData.status === "paused" && (
              <span className="flex items-center gap-1.5 text-xs text-yellow-400 font-bold tracking-wider animate-pulse">
                {t.telemetry.gpPaused}
              </span>
            )}
            {raceData.status === "finished" && (
              <span className="flex items-center gap-1.5 text-xs text-rose-400 font-bold tracking-wider">
                {t.telemetry.finalResults}
              </span>
            )}
          </div>

          <div className="glass-panel rounded-3xl overflow-hidden">
            {leaderboard.length === 0 ? (
              <div className="p-12 text-center text-slate-500 text-sm">
                {t.telemetry.noLapsRecorded}
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                  <thead>
                    <tr className="border-b border-white/5 bg-slate-900/30 text-[10px] text-slate-400 font-black uppercase tracking-wider">
                      <th className="p-4 sm:p-5 text-center w-16">{t.telemetry.pos}</th>
                      <th className="p-4 sm:p-5">{t.telemetry.driverCar}</th>
                      <th className="p-4 sm:p-5 text-center w-24">{t.telemetry.fenda}</th>
                      <th className="p-4 sm:p-5 text-center w-24">{t.telemetry.lapsCount}</th>
                      <th className="p-4 sm:p-5 text-right w-32">{t.telemetry.bestLap}</th>
                      <th className="p-4 sm:p-5 text-right w-36">{t.telemetry.totalTime}</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-white/5 text-sm">
                    {leaderboard.map((item, idx) => {
                      const colorIdx = (item.lane_number - 1) % 4;
                      
                      // Best lap formatting
                      const isBestLapGlobal = leaderboard.length > 0 && 
                        item.best_lap !== null && 
                        item.best_lap === Math.min(...leaderboard.filter(l => l.best_lap !== null).map(l => l.best_lap));

                      return (
                        <tr key={item.driver_id} className="hover:bg-white/1 transition-all">
                          {/* Position */}
                          <td className="p-4 sm:p-5 text-center">
                            <span className={`w-8 h-8 rounded-full flex items-center justify-center font-black font-mono-telemetry text-sm ${
                              idx === 0 
                                ? "bg-yellow-400 text-black shadow-lg shadow-yellow-400/20" 
                                : idx === 1 
                                ? "bg-slate-300 text-black" 
                                : idx === 2 
                                ? "bg-amber-600 text-white" 
                                : "bg-slate-900 border border-white/5 text-slate-400"
                            }`}>
                              {idx + 1}
                            </span>
                          </td>

                          {/* Driver Name & Car */}
                          <td className="p-4 sm:p-5">
                            <div className="flex items-center gap-3">
                              <div className="w-9 h-9 rounded-full border border-white/5 overflow-hidden bg-slate-900 flex items-center justify-center text-xs font-bold text-slate-300 flex-shrink-0">
                                {item.driver_avatar_url ? (
                                  <img src={item.driver_avatar_url} alt={item.driver_name} className="w-full h-full object-cover" />
                                ) : (
                                  <span>{item.driver_nickname?.substring(0,2).toUpperCase() || item.driver_name.substring(0,2).toUpperCase()}</span>
                                )}
                              </div>
                              <div>
                                <span className="font-extrabold text-slate-200 block text-base leading-tight">
                                  {item.driver_name} 
                                  {item.driver_nickname && <span className="text-xs text-slate-400 font-semibold ml-1.5">({item.driver_nickname})</span>}
                                </span>
                                <span className="text-xs text-slate-400 font-medium block mt-0.5">{item.car_name}</span>
                              </div>
                            </div>
                          </td>

                          {/* Lane */}
                          <td className="p-4 sm:p-5 text-center">
                            <span className={`inline-block px-2.5 py-0.5 rounded border text-[10px] font-black uppercase ${laneBadgeColors[colorIdx]}`}>
                              {t.telemetry.fenda} {item.lane_number}
                            </span>
                          </td>

                          {/* Laps */}
                          <td className="p-4 sm:p-5 text-center font-extrabold text-slate-200 font-mono-telemetry text-base">
                            {item.laps_completed}
                          </td>

                          {/* Best Lap */}
                          <td className="p-4 sm:p-5 text-right font-mono-telemetry font-extrabold text-sm">
                            {item.best_lap ? (
                              <span className="flex items-center justify-end gap-1.5">
                                {isBestLapGlobal && <span className="text-[10px] uppercase font-black text-rose-400 bg-rose-500/10 px-1.5 py-0.5 rounded border border-rose-500/20 animate-pulse">{t.telemetry.bestBadge}</span>}
                                <span className={isBestLapGlobal ? "text-rose-400" : "text-slate-300"}>
                                  {item.best_lap.toFixed(3)}s
                                </span>
                              </span>
                            ) : (
                              <span className="text-slate-600 font-normal">--.---</span>
                            )}
                          </td>

                          {/* Total Time */}
                          <td className="p-4 sm:p-5 text-right font-mono-telemetry text-slate-300 font-semibold">
                            {item.total_time > 0 ? `${item.total_time.toFixed(3)}s` : "--.---"}
                          </td>
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>

      </div>

      {/* Detailed Lap History Section */}
      <div className="space-y-4 pt-4 border-t border-white/5">
        <div className="flex items-center justify-between">
          <h2 className="text-lg font-bold text-slate-200 uppercase tracking-tight">
            {t.telemetry.detailedHistory}
          </h2>
          <span className="text-xs text-slate-500 font-semibold uppercase tracking-wider">
            {t.telemetry.historySub}
          </span>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
          {raceData.participants?.map((part) => {
            const colorIdx = (part.lane_number - 1) % 4;
            
            // Find the driver's leaderboard data to get their recorded laps
            const driverStats = leaderboard.find(l => l.driver_id === part.driver_id);
            const lapsList = driverStats?.laps || [];
            
            return (
              <div key={part.driver_id} className={`glass-panel p-5 rounded-2xl space-y-4 ${laneGlowClasses[colorIdx]}`}>
                {/* Header info */}
                <div className="flex items-center justify-between border-b border-white/5 pb-3">
                  <div>
                    <h4 className="font-extrabold text-slate-200 text-sm leading-tight">{part.driver?.name}</h4>
                    <span className="text-[10px] text-slate-500 font-semibold block mt-0.5">{part.car?.name}</span>
                  </div>
                  <span className={`text-[9px] uppercase font-black px-2 py-0.5 rounded border ${laneBadgeColors[colorIdx]}`}>
                    {t.telemetry.fenda} {part.lane_number}
                  </span>
                </div>

                {/* Laps List */}
                <div className="max-h-64 overflow-y-auto space-y-2 pr-1 scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">
                  {lapsList.length === 0 ? (
                    <div className="text-center py-12 text-xs text-slate-500 italic">
                      {t.telemetry.noLapsYet}
                    </div>
                  ) : (
                    // Reverse the laps list to show the most recent lap first (live feed experience)
                    [...lapsList].reverse().map((lap) => {
                      const isBestLap = driverStats.best_lap !== null && lap.lap_time_seconds === driverStats.best_lap;
                      
                      return (
                        <div 
                          key={lap.lap_number} 
                          className={`flex items-center justify-between p-2.5 rounded-xl text-xs font-semibold transition-all ${
                            isBestLap 
                              ? "bg-rose-500/10 border border-rose-500/20 text-rose-400 shadow-sm shadow-rose-500/5" 
                              : "bg-slate-900/50 border border-white/5 text-slate-300"
                          }`}
                        >
                          <span className="text-slate-500">Lap #{lap.lap_number}</span>
                          <span className="font-mono-telemetry font-bold flex items-center gap-1">
                            {isBestLap && <span className="text-[9px] font-black px-1.5 py-0.5 rounded bg-rose-500/15 text-rose-400 border border-rose-500/10 animate-pulse">{t.telemetry.bestBadge}</span>}
                            {lap.lap_time_seconds.toFixed(3)}s
                          </span>
                        </div>
                      );
                    })
                  )}
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}
