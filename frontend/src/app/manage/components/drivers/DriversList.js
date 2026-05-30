"use client";

import { useTranslation } from "@/context/LanguageContext";

export default function DriversList({ drivers, onEdit, onDelete }) {
  const { t, lang } = useTranslation();

  if (drivers.length === 0) {
    return (
      <div className="glass-panel p-8 text-center text-slate-500 text-sm rounded-2xl border border-white/5">
        {t.database.noDrivers}
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {drivers.map((d) => (
        <div 
          key={d.id} 
          className="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-white/10 transition-colors flex flex-col justify-between"
        >
          <div>
            <div className="flex items-center gap-4">
              {/* Circular Avatar */}
              <div className="w-20 h-20 rounded-full border-2 border-white/5 shadow-md overflow-hidden bg-slate-900/60 flex-shrink-0 flex items-center justify-center relative">
                {d.avatar_url ? (
                  <img src={d.avatar_url} alt={d.name} className="w-full h-full object-cover" />
                ) : (
                  <span className="font-extrabold text-lg text-cyan-400 font-mono-telemetry bg-cyan-500/10 w-full h-full flex items-center justify-center">
                    {d.nickname?.substring(0, 2).toUpperCase() || d.name.substring(0, 2).toUpperCase()}
                  </span>
                )}
              </div>
              <div>
                <span className="text-[10px] text-slate-500 font-bold uppercase tracking-widest block">{t.driver.driver}</span>
                <h3 className="text-lg font-black text-slate-200 mt-0.5 leading-tight">{d.name}</h3>
                {d.nickname && <p className="text-xs text-cyan-400 font-semibold mt-0.5">{t.driver.nickname}: {d.nickname}</p>}
              </div>
            </div>
          </div>
          
          <div className="flex items-center justify-between border-t border-white/5 mt-4 pt-4">
            <div className="flex gap-6 text-xs text-slate-400 font-semibold">
              <span>
                🏁 {t.driver.gps}: <strong className="text-slate-200 font-mono-telemetry">{d.races_count || 0}</strong>
              </span>
              <span>
                ⚡ {t.driver.laps}: <strong className="text-slate-200 font-mono-telemetry">{d.total_laps || 0}</strong>
              </span>
            </div>
            <div className="flex items-center gap-1.5">
              <button
                type="button"
                onClick={() => onEdit(d)}
                className="text-cyan-500 hover:text-cyan-400 p-1.5 rounded-lg hover:bg-cyan-500/10 transition-colors"
                title={t.driver.editDriver}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </button>
              <button
                type="button"
                onClick={() => onDelete(d.id, d.name)}
                className="text-rose-500 hover:text-rose-400 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors"
                title="Delete Driver"
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
