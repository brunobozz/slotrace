"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { api } from "@/services/api";
import { useAuth } from "../../context/AuthContext";
import { useTranslation } from "@/context/LanguageContext";

export default function CreateRace() {
  const router = useRouter();
  const { user } = useAuth();
  const { t, lang } = useTranslation();
  
  // Data lists from API
  const [tracks, setTracks] = useState([]);
  const [drivers, setDrivers] = useState([]);
  const [cars, setCars] = useState([]);
  
  // Form states
  const [selectedTrackId, setSelectedTrackId] = useState("");
  const [raceName, setRaceName] = useState("");
  const [raceType, setRaceType] = useState("lap_race");
  const [lapsLimit, setLapsLimit] = useState(10);
  const [durationSeconds, setDurationSeconds] = useState(600); // 10 min
  
  // Participants slots (dynamic based on selected track lanes)
  const [participants, setParticipants] = useState([]);
  
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);
  const [isAuthorized, setIsAuthorized] = useState(false);

  const checkAuth = () => {
    if (typeof window === "undefined") return;
    const token = sessionStorage.getItem("slotrace_drive_token");
    const isSim = user?.auth_type?.includes("simulated");
    setIsAuthorized(!!token || isSim);
  };

  useEffect(() => {
    checkAuth();
    window.addEventListener("slotrace_token_updated", checkAuth);
    return () => window.removeEventListener("slotrace_token_updated", checkAuth);
  }, [user]);

  useEffect(() => {
    const fetchData = async () => {
      const token = sessionStorage.getItem("slotrace_drive_token");
      const isSim = user?.auth_type?.includes("simulated");
      if (!token && !isSim) {
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        const [tracksData, driversData, carsData] = await Promise.all([
          api.tracks.list().catch(() => []),
          api.drivers.list().catch(() => []),
          api.cars.list().catch(() => []),
        ]);
        
        setTracks(tracksData);
        setDrivers(driversData);
        setCars(carsData);
        
        if (tracksData.length > 0) {
          // Select first track by default
          const firstTrack = tracksData[0];
          setSelectedTrackId(firstTrack.id);
          
          // Setup slots
          const slots = [];
          for (let i = 1; i <= firstTrack.lanes_count; i++) {
            slots.push({
              lane_number: i,
              driver_id: "",
              car_id: ""
            });
          }
          setParticipants(slots);
        }
        setError(null);
      } catch (err) {
        console.error(err);
        setError("Error loading configurator.");
      } finally {
        setLoading(false);
      }
    };
    
    if (isAuthorized) {
      fetchData();
    } else {
      setLoading(false);
    }
  }, [isAuthorized]);

  const setupParticipantsSlots = (track) => {
    const slots = [];
    for (let i = 1; i <= track.lanes_count; i++) {
      slots.push({
        lane_number: i,
        driver_id: "",
        car_id: ""
      });
    }
    setParticipants(slots);
  };

  const handleTrackChange = (e) => {
    const trackId = e.target.value;
    setSelectedTrackId(trackId);
    const track = tracks.find(trackItem => trackItem.id == trackId);
    if (track) {
      setupParticipantsSlots(track);
    }
  };

  const handleParticipantChange = (index, field, value) => {
    const updated = [...participants];
    updated[index][field] = value;
    
    // Auto-select first car owned by driver if driver changes
    if (field === "driver_id" && value !== "") {
      const driverCars = cars.filter(c => c.driver_id == value);
      if (driverCars.length > 0) {
        updated[index]["car_id"] = driverCars[0].id;
      } else {
        updated[index]["car_id"] = "";
      }
    }
    
    setParticipants(updated);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);

    // Validate inputs
    if (!selectedTrackId) return setError(t.createRace.errorTrack);
    if (!raceName.trim()) return setError(t.createRace.errorName);
    
    // Check if at least 1 participant is selected
    const activeParticipants = participants.filter(p => p.driver_id !== "" && p.car_id !== "");
    if (activeParticipants.length < 1) {
      return setError(t.createRace.errorParticipants);
    }

    // Check for duplicate drivers
    const selectedDriverIds = activeParticipants.map(p => p.driver_id);
    const hasDuplicates = selectedDriverIds.some((val, i) => selectedDriverIds.indexOf(val) !== i);
    if (hasDuplicates) {
      return setError(t.createRace.errorDuplicate);
    }

    setSubmitting(true);
    
    const submitData = {
      track_id: selectedTrackId,
      name: raceName,
      type: raceType,
      laps_limit: raceType === "lap_race" ? parseInt(lapsLimit) : null,
      duration_seconds: raceType === "endurance" ? parseInt(durationSeconds) : null,
      participants: activeParticipants.map(p => ({
        driver_id: p.driver_id,
        car_id: p.car_id,
        lane_number: p.lane_number
      }))
    };

    try {
      const localId = `local_${Date.now()}`;
      localStorage.setItem(`local_race_${localId}`, JSON.stringify(submitData));
      router.push(`/races/${localId}`);
    } catch (err) {
      console.error(err);
      setError(err.message || "Error trying to create race.");
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[50vh] gap-4">
        <div className="w-12 h-12 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
        <span className="text-slate-400 font-semibold tracking-wider animate-pulse">{t.telemetry.receivers}</span>
      </div>
    );
  }

  if (!isAuthorized) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[60vh] text-center p-8 glass-panel max-w-md mx-auto space-y-6 rounded-3xl border border-white/10 mt-10 animate-in fade-in zoom-in-95 duration-200">
        <div className="w-16 h-16 mx-auto rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
          <svg className="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <div className="space-y-2">
          <h3 className="text-lg font-bold text-slate-200">{t.database.restrictedTitle}</h3>
          <p className="text-xs text-slate-400 leading-relaxed">
            {t.database.restrictedDesc}
          </p>
        </div>
        <button
          onClick={() => window.dispatchEvent(new Event("slotrace_open_sync"))}
          className="px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-extrabold uppercase text-[10px] tracking-wider rounded-xl shadow-lg shadow-cyan-500/10 transition-all active:scale-95"
        >
          {t.database.connectBtn}
        </button>
      </div>
    );
  }

  const selectedTrack = tracks.find(trackItem => trackItem.id == selectedTrackId);

  return (
    <div className="max-w-4xl mx-auto space-y-8">
      {/* Header */}
      <div className="space-y-2">
        <h1 className="text-3xl font-extrabold tracking-tight text-white">{t.createRace.title}</h1>
        <p className="text-slate-400 text-sm">
          {t.createRace.subtitle}
        </p>
      </div>

      {error && (
        <div className="p-4 rounded-xl border border-rose-500/30 bg-rose-500/10 text-rose-300 text-sm flex items-center gap-3">
          <svg className="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-8">
        
        {/* Step 1: Circuit & Rules (Glass Panel) */}
        <div className="glass-panel p-6 rounded-2xl space-y-6">
          <h2 className="text-lg font-bold text-slate-200 border-b border-white/5 pb-3">
            {t.createRace.step1}
          </h2>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {/* Select Track */}
            <div className="space-y-2">
              <label className="text-xs font-semibold text-slate-400 uppercase tracking-wider block">{t.createRace.trackLabel}</label>
              <select
                value={selectedTrackId}
                onChange={handleTrackChange}
                className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all font-semibold"
              >
                {tracks.map(trackItem => (
                  <option key={trackItem.id} value={trackItem.id}>{trackItem.name} ({trackItem.lanes_count} {t.dashboard.trackLanes})</option>
                ))}
              </select>
            </div>

            {/* Race Name */}
            <div className="space-y-2">
              <label className="text-xs font-semibold text-slate-400 uppercase tracking-wider block">{t.createRace.gpName}</label>
              <input
                type="text"
                placeholder={t.createRace.placeholderGpName}
                value={raceName}
                onChange={(e) => setRaceName(e.target.value)}
                className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all font-medium placeholder:text-slate-600"
              />
            </div>

            {/* Race Type */}
            <div className="space-y-2">
              <label className="text-xs font-semibold text-slate-400 uppercase tracking-wider block">{t.createRace.raceMode}</label>
              <select
                value={raceType}
                onChange={(e) => setRaceType(e.target.value)}
                className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all font-semibold"
              >
                <option value="lap_race">{t.createRace.modeLapLimit}</option>
                <option value="time_trial">{t.createRace.modeTimeTrial}</option>
                <option value="endurance">{t.createRace.modeEndurance}</option>
              </select>
            </div>

            {/* Condition Limits */}
            {raceType === "lap_race" && (
              <div className="space-y-2">
                <label className="text-xs font-semibold text-slate-400 uppercase tracking-wider block">{t.createRace.lapLimitLabel}</label>
                <input
                  type="number"
                  min="1"
                  value={lapsLimit}
                  onChange={(e) => setLapsLimit(e.target.value)}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all font-mono-telemetry font-bold"
                />
              </div>
            )}

            {raceType === "endurance" && (
              <div className="space-y-2">
                <label className="text-xs font-semibold text-slate-400 uppercase tracking-wider block">{t.createRace.timeLimitLabel}</label>
                <input
                  type="number"
                  min="1"
                  value={durationSeconds}
                  onChange={(e) => setDurationSeconds(e.target.value)}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-slate-200 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all font-mono-telemetry font-bold"
                />
              </div>
            )}
            
          </div>
        </div>

        {/* Step 2: Grid & Lanes Allocation */}
        <div className="glass-panel p-6 rounded-2xl space-y-6">
          <div>
            <h2 className="text-lg font-bold text-slate-200">
              {t.createRace.step2}
            </h2>
            <p className="text-xs text-slate-500 mt-1">
              {t.createRace.gridSub} {selectedTrack ? `"${selectedTrack.name}"` : ""}.
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-white/5 pt-6">
            {participants.map((part, index) => {
              const laneGlowClasses = [
                "border-l-[6px] border-l-cyan-400/80 shadow-sm shadow-cyan-400/5",
                "border-l-[6px] border-l-rose-500/80 shadow-sm shadow-rose-500/5",
                "border-l-[6px] border-l-emerald-500/80 shadow-sm shadow-emerald-500/5",
                "border-l-[6px] border-l-yellow-400/80 shadow-sm shadow-yellow-400/5"
              ];
              const laneTextColors = ["text-cyan-400", "text-rose-500", "text-emerald-400", "text-yellow-400"];

              return (
                <div key={index} className={`glass-card p-5 rounded-2xl space-y-4 ${laneGlowClasses[index % 4]}`}>
                  <div className="flex items-center justify-between">
                    <span className="text-sm font-black tracking-wider uppercase flex items-center gap-2">
                      🚥 {t.createRace.laneLabel} <span className={`text-lg font-black ${laneTextColors[index % 4]}`}>{part.lane_number}</span>
                    </span>
                    <span className="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                      {t.createRace.slotLabel} {part.lane_number}
                    </span>
                  </div>

                  {/* Driver Select */}
                  <div className="space-y-1.5">
                    <label className="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">{t.driver.driver}</label>
                    <select
                      value={part.driver_id}
                      onChange={(e) => handleParticipantChange(index, "driver_id", e.target.value)}
                      className="w-full px-3 py-2.5 rounded-xl bg-slate-950/80 border border-white/5 text-slate-300 text-sm focus:outline-none focus:border-cyan-500 transition-all font-semibold"
                    >
                      <option value="">{t.createRace.selectDriver}</option>
                      {drivers.map(d => (
                        <option key={d.id} value={d.id}>{d.name} {d.nickname ? `(${d.nickname})` : ""}</option>
                      ))}
                    </select>
                  </div>

                  {/* Car Select */}
                  <div className="space-y-1.5">
                    <label className="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">{t.car.car}</label>
                    <select
                      value={part.car_id}
                      onChange={(e) => handleParticipantChange(index, "car_id", e.target.value)}
                      disabled={!part.driver_id}
                      className="w-full px-3 py-2.5 rounded-xl bg-slate-950/80 border border-white/5 text-slate-300 text-sm focus:outline-none focus:border-cyan-500 transition-all disabled:opacity-40 font-semibold"
                    >
                      <option value="">{t.createRace.selectCar}</option>
                      {/* Filter cars belonging to selected driver first, then others */}
                      {part.driver_id && cars.filter(c => c.driver_id == part.driver_id).map(c => (
                        <option key={c.id} value={c.id}>{c.name} [{c.brand}]</option>
                      ))}
                      <option value="" disabled>{t.createRace.otherCars}</option>
                      {cars.filter(c => c.driver_id != part.driver_id).map(c => (
                        <option key={c.id} value={c.id}>{c.name} {c.driver ? `(owner: ${c.driver.nickname || c.driver.name})` : ""}</option>
                      ))}
                    </select>
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Form Actions */}
        <div className="flex items-center justify-end gap-4">
          <button
            type="button"
            onClick={() => router.push("/")}
            className="px-6 py-3.5 rounded-xl text-sm font-bold text-slate-400 hover:text-white transition-all"
          >
            {t.createRace.cancel}
          </button>
          
          <button
            type="submit"
            disabled={submitting}
            className="px-8 py-3.5 rounded-xl text-sm font-extrabold bg-gradient-to-r from-cyan-400 to-blue-500 hover:from-cyan-300 hover:to-blue-400 text-black shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 disabled:opacity-50 active:scale-95 transition-all"
          >
            {submitting ? t.createRace.aligning : t.createRace.createBtn}
          </button>
        </div>

      </form>
    </div>
  );
}
