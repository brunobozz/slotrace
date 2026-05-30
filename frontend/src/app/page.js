"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { api } from "@/services/api";
import { useTranslation } from "@/context/LanguageContext";

export default function Dashboard() {
  const { t, lang } = useTranslation();
  const [stats, setStats] = useState({ drivers: 0, tracks: 0, races: 0 });
  const [tracks, setTracks] = useState([]);
  const [races, setRaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [driversData, tracksData, racesData] = await Promise.all([
        api.drivers.list().catch(() => []),
        api.tracks.list().catch(() => []),
        api.races.list().catch(() => []),
      ]);

      setStats({
        drivers: driversData.length,
        tracks: tracksData.length,
        races: racesData.length,
      });
      setTracks(tracksData);
      setRaces(racesData);
      setError(null);
    } catch (err) {
      console.error(err);
      setError("Error loading dashboard data. Make sure the Laravel server is running.");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();

    const handleRestore = () => {
      fetchData();
    };

    window.addEventListener("slotrace_data_restored", handleRestore);
    return () => window.removeEventListener("slotrace_data_restored", handleRestore);
  }, []);

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[50vh] gap-4">
        <div className="w-12 h-12 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
        <span className="text-slate-400 font-semibold tracking-wider animate-pulse">{t.telemetry.receivers}</span>
      </div>
    );
  }

  return (
    <div className="space-y-10">
      {/* Welcome / Quick Actions */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-slate-900 to-slate-950 border border-white/5 p-8 rounded-3xl relative overflow-hidden">
        <div className="absolute top-0 right-0 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl -z-10"></div>
        <div className="absolute bottom-0 left-10 w-48 h-48 bg-rose-500/10 rounded-full blur-3xl -z-10"></div>
        
        <div className="space-y-2">
          <h1 className="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-100 to-slate-300">
            {t.dashboard.title}
          </h1>
          <p className="text-slate-400 text-sm max-w-xl leading-relaxed">
            {t.dashboard.subtitle}
          </p>
        </div>

        <div>
          <Link
            href="/races/create"
            className="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl text-sm font-bold bg-gradient-to-r from-cyan-400 to-blue-500 hover:from-cyan-300 hover:to-blue-400 text-black shadow-xl shadow-cyan-500/20 active:scale-95 transition-all duration-200"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
              <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            {t.dashboard.newGpButton}
          </Link>
        </div>
      </div>

      {error && (
        <div className="p-4 rounded-xl border border-rose-500/30 bg-rose-500/10 text-rose-300 text-sm flex items-center gap-3">
          <svg className="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          {error}
        </div>
      )}

      {/* Onboarding Empty State */}
      {stats.drivers === 0 && stats.tracks === 0 && (
        <div className="glass-panel p-8 rounded-3xl border border-dashed border-white/10 text-center space-y-6 max-w-xl mx-auto py-12 relative overflow-hidden">
          <div className="absolute top-0 right-0 w-32 h-32 bg-cyan-500/5 rounded-full blur-2xl -z-10" />
          <div className="w-16 h-16 mx-auto rounded-2xl bg-slate-900/80 border border-white/5 flex items-center justify-center text-cyan-400">
            <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
            </svg>
          </div>
          <div className="space-y-2">
            <h3 className="text-lg font-bold text-slate-200">{t.dashboard.emptyStateTitle}</h3>
            <p className="text-xs text-slate-400 max-w-sm mx-auto leading-relaxed">
              {t.dashboard.emptyStateSubtitle}
            </p>
          </div>
          <div className="flex gap-3 justify-center">
            <Link
              href="/manage"
              className="px-5 py-2.5 rounded-xl text-xs font-bold bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-all"
            >
              {t.dashboard.createRecordsBtn}
            </Link>
            <button
              onClick={() => {
                window.dispatchEvent(new Event("slotrace_open_sync"));
              }}
              className="px-5 py-2.5 rounded-xl text-xs font-bold bg-cyan-500 hover:bg-cyan-400 text-black shadow-lg shadow-cyan-500/10 active:scale-95 transition-all"
            >
              {t.dashboard.connectDriveBtn}
            </button>
          </div>
        </div>
      )}

      {/* Stats Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
        {[
          { label: t.dashboard.statsDrivers, count: stats.drivers, color: "from-cyan-500 to-blue-500" },
          { label: t.dashboard.statsTracks, count: stats.tracks, color: "from-rose-500 to-orange-500" },
          { label: t.dashboard.statsRaces, count: stats.races, color: "from-emerald-500 to-teal-500" },
        ].map((item, idx) => (
          <div key={idx} className="glass-panel p-6 rounded-2xl relative overflow-hidden group">
            <div className={`absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b ${item.color}`}></div>
            <span className="text-slate-400 text-xs font-semibold uppercase tracking-wider block">
              {item.label}
            </span>
            <span className="text-4xl font-extrabold tracking-tight block mt-2 text-white font-mono-telemetry">
              {String(item.count).padStart(2, "0")}
            </span>
          </div>
        ))}
      </div>

      {/* Grid: Records & History */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {/* Track Records (Left Column - 1/3) */}
        <div className="space-y-5 lg:col-span-1">
          <div className="flex items-center justify-between">
            <h2 className="text-lg font-bold tracking-tight text-slate-200 uppercase">
              {t.dashboard.recordsTitle}
            </h2>
          </div>

          <div className="space-y-4">
            {tracks.length === 0 ? (
              <div className="glass-panel p-6 rounded-2xl text-center text-slate-500 text-sm">
                {t.dashboard.noTracks}
              </div>
            ) : (
              tracks.map((track) => (
                <div key={track.id} className="glass-panel p-5 rounded-2xl space-y-4 relative group">
                  <div className="flex items-start justify-between">
                    <div>
                      <h3 className="font-bold text-slate-200">{track.name}</h3>
                      <p className="text-xs text-slate-400 mt-0.5">
                        {track.lanes_count} {t.dashboard.trackLanes} • {track.length_meters ? `${track.length_meters}m` : t.dashboard.trackLengthNa}
                      </p>
                    </div>
                    <span className="text-[10px] uppercase font-bold tracking-widest text-cyan-400 px-2 py-0.5 rounded border border-cyan-500/20 bg-cyan-500/5">
                      {t.dashboard.trackType}
                    </span>
                  </div>

                  {track.best_lap_time ? (
                    <div className="flex items-center justify-between p-3 rounded-xl bg-slate-900/50 border border-white/5">
                      <div className="flex items-center gap-3">
                        <div className="w-8 h-8 rounded-full border border-white/5 overflow-hidden bg-rose-500/10 flex items-center justify-center text-rose-400 text-xs font-bold font-mono-telemetry flex-shrink-0">
                          {track.best_lap_driver?.avatar_url ? (
                            <img src={track.best_lap_driver.avatar_url} alt={track.best_lap_driver.name} className="w-full h-full object-cover" />
                          ) : (
                            <span>{track.best_lap_driver?.nickname?.substring(0,2).toUpperCase() || "DR"}</span>
                          )}
                        </div>
                        <div>
                          <p className="text-xs text-slate-400">{t.dashboard.recordist}</p>
                          <p className="text-sm font-semibold text-slate-200">{track.best_lap_driver?.name}</p>
                        </div>
                      </div>
                      <div className="text-right">
                        <p className="text-[10px] text-slate-500 font-bold uppercase tracking-wider">{t.track.record}</p>
                        <p className="text-base font-extrabold text-rose-400 font-mono-telemetry">
                          {parseFloat(track.best_lap_time).toFixed(3)}s
                        </p>
                      </div>
                    </div>
                  ) : (
                    <div className="text-center p-3 rounded-xl bg-slate-900/35 border border-white/5 text-xs text-slate-500">
                      {t.dashboard.noRecords}
                    </div>
                  )}
                </div>
              ))
            )}
          </div>
        </div>

        {/* Races History (Right Column - 2/3) */}
        <div className="space-y-5 lg:col-span-2">
          <div className="flex items-center justify-between">
            <h2 className="text-lg font-bold tracking-tight text-slate-200 uppercase">
              {t.dashboard.historyTitle}
            </h2>
          </div>

          <div className="glass-panel rounded-2xl overflow-hidden">
            {races.length === 0 ? (
              <div className="p-12 text-center text-slate-500 text-sm">
                {t.dashboard.noRaces}
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                  <thead>
                    <tr className="border-b border-white/5 bg-slate-900/30 text-xs text-slate-400 font-bold uppercase tracking-wider">
                      <th className="p-4 sm:p-5">{t.dashboard.tableName}</th>
                      <th className="p-4 sm:p-5">{t.dashboard.tableTrack}</th>
                      <th className="p-4 sm:p-5">{t.dashboard.tableType}</th>
                      <th className="p-4 sm:p-5 text-center">{t.dashboard.tableStatus}</th>
                      <th className="p-4 sm:p-5 text-right">{t.dashboard.tableAction}</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-white/5 text-sm">
                    {races.map((race) => (
                      <tr key={race.id} className="hover:bg-white/1 transition-all group">
                        <td className="p-4 sm:p-5">
                          <span className="font-bold text-slate-200 block">{race.name}</span>
                          <span className="text-xs text-slate-400 mt-0.5">
                            {race.participants_count} {t.dashboard.pilotsCount} • {new Date(race.created_at).toLocaleDateString("en-US")}
                          </span>
                        </td>
                        <td className="p-4 sm:p-5 text-slate-300 font-medium">
                          {race.track?.name}
                        </td>
                        <td className="p-4 sm:p-5">
                          <span className="text-xs text-slate-400 capitalize bg-slate-800 px-2.5 py-1 rounded-md">
                            {race.type === "lap_race" ? t.createRace.modeLapLimit : race.type === "time_trial" ? t.createRace.modeTimeTrial : t.createRace.modeEndurance}
                          </span>
                        </td>
                        <td className="p-4 sm:p-5 text-center">
                          {race.status === "finished" ? (
                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                              {t.dashboard.finishedStatus}
                            </span>
                          ) : race.status === "in_progress" ? (
                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 animate-pulse">
                              {t.dashboard.inProgressStatus}
                            </span>
                          ) : race.status === "paused" ? (
                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 animate-pulse">
                              {t.dashboard.pausedStatus}
                            </span>
                          ) : (
                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                              {t.dashboard.pendingStatus}
                            </span>
                          )}
                        </td>
                        <td className="p-4 sm:p-5 text-right">
                          <Link
                            href={`/races/${race.id}`}
                            className={`inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all ${
                              race.status === "in_progress" || race.status === "paused"
                                ? "bg-emerald-500 text-black hover:bg-emerald-400 shadow-lg shadow-emerald-500/10"
                                : "bg-white/5 hover:bg-white/10 text-slate-200"
                            }`}
                          >
                            {race.status === "in_progress" || race.status === "paused" ? t.dashboard.btnResume : t.dashboard.btnView}
                            <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                          </Link>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>

      </div>
    </div>
  );
}
