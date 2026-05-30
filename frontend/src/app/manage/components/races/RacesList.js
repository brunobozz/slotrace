"use client";

import { useTranslation } from "@/context/LanguageContext";

export default function RacesList({ races, onDelete }) {
  const { t, lang } = useTranslation();

  if (races.length === 0) {
    return (
      <div className="glass-panel p-8 text-center text-slate-500 text-sm rounded-2xl border border-white/5">
        {t.database.noRaces}
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {races.map((r) => (
        <div 
          key={r.id} 
          className="glass-panel p-5 rounded-2xl relative overflow-hidden group flex flex-col justify-between hover:border-white/10 transition-colors"
        >
          <div>
            <div className="flex items-start justify-between">
              <span className="text-[10px] text-slate-500 font-bold uppercase tracking-widest block">{t.race.gp}</span>
              {r.status === "finished" ? (
                <span className="px-2.5 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                  {t.race.finished}
                </span>
              ) : r.status === "in_progress" ? (
                <span className="px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 animate-pulse">
                  {t.race.inProgress}
                </span>
              ) : r.status === "paused" ? (
                <span className="px-2.5 py-0.5 rounded text-[10px] font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 animate-pulse">
                  {t.race.paused}
                </span>
              ) : (
                <span className="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                  {t.race.pending}
                </span>
              )}
            </div>
            <h3 className="text-base font-black text-slate-200 mt-2">{r.name}</h3>
            <p className="text-xs text-slate-400 mt-0.5">{t.race.track}: <strong className="text-slate-300 font-semibold">{r.track?.name}</strong></p>
          </div>

          <div className="flex items-center justify-between border-t border-white/5 mt-4 pt-4">
            <div className="flex flex-col text-xs text-slate-400 gap-1">
              <span>{t.race.type}: <strong className="text-slate-300">{r.type === "lap_race" ? t.createRace.modeLapLimit : r.type === "time_trial" ? t.createRace.modeTimeTrial : t.createRace.modeEndurance}</strong></span>
              <span>{t.race.date}: <strong className="text-slate-300 font-mono-telemetry">{new Date(r.created_at).toLocaleDateString("en-US")}</strong></span>
            </div>
            <div className="flex items-center gap-2">
              <a
                href={`/races/${r.id}`}
                className="px-3 py-1.5 rounded-xl text-xs font-bold bg-white/5 hover:bg-white/10 text-slate-200 hover:text-white transition-all"
              >
                {t.race.dashboard}
              </a>
              <button
                type="button"
                onClick={() => onDelete(r.id, r.name)}
                className="text-rose-500 hover:text-rose-400 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors"
                title={t.race.deleteRace}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}
