"use client";

import { useTranslation } from "@/context/LanguageContext";

export default function TracksList({ tracks, onEdit, onDelete }) {
  const { t, lang } = useTranslation();

  if (tracks.length === 0) {
    return (
      <div className="glass-panel p-8 text-center text-slate-500 text-sm rounded-2xl border border-white/5">
        {t.database.noTracks}
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {tracks.map((tItem) => (
        <div 
          key={tItem.id} 
          className="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-white/10 transition-colors flex flex-col justify-between"
        >
          <div>
            {tItem.image_url ? (
              <div className="w-full h-36 rounded-xl overflow-hidden mb-4 border border-white/5 bg-slate-955/60 relative">
                <img src={tItem.image_url} alt={tItem.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
              </div>
            ) : (
              <div className="w-full h-36 rounded-xl mb-4 border border-white/5 bg-slate-955/40 relative flex flex-col items-center justify-center text-slate-600 group-hover:text-cyan-500/50 transition-colors">
                <svg className="w-10 h-10 mb-2 opacity-30 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="1.5">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 2.25-.222a1.125 1.125 0 0 0 .997-1.07V3.535a1.125 1.125 0 0 0-1.251-1.115l-4.5.45a1.125 1.125 0 0 0-.999 1.116V16.37m-6 .223-4.5-.45A1.125 1.125 0 0 1 2.25 15V4.665c0-.573.432-1.054 1-.106l4.5.45c.57.057 1 .538 1 1.116V16.59" />
                </svg>
                <span className="text-[10px] uppercase font-bold tracking-widest opacity-40">{t.track.noLayout}</span>
              </div>
            )}
            
            <span className="text-[10px] text-slate-500 font-bold uppercase tracking-widest block">{t.track.track}</span>
            <h3 className="text-base font-black text-slate-200 mt-1 leading-tight">{tItem.name}</h3>
            
            <div className="grid grid-cols-2 gap-4 mt-3 border-t border-white/5 pt-3 text-xs text-slate-400">
              <div>
                <p>{t.track.lanes}</p>
                <p className="text-sm font-extrabold text-slate-200 font-mono-telemetry">{tItem.lanes_count}</p>
              </div>
              <div>
                <p>{t.track.length}</p>
                <p className="text-sm font-extrabold text-slate-200 font-mono-telemetry">{tItem.length_meters ? `${tItem.length_meters}m` : "N/A"}</p>
              </div>
            </div>
          </div>

          <div className="flex items-center justify-between mt-3 pt-3 border-t border-white/5">
            <div className="flex-1 mr-4">
              {tItem.best_lap_time ? (
                <div className="bg-slate-900/50 p-2 rounded-xl border border-white/5 text-[11px] flex justify-between items-center">
                  <span className="text-slate-400">🏆 {t.track.record}:</span>
                  <strong className="text-rose-400 font-mono-telemetry">{parseFloat(tItem.best_lap_time).toFixed(3)}s ({tItem.best_lap_driver?.nickname || tItem.best_lap_driver?.name})</strong>
                </div>
              ) : (
                <span className="text-slate-500 text-xs italic">{t.track.noRecord}</span>
              )}
            </div>
            
            <div className="flex items-center gap-1.5">
              <button
                type="button"
                onClick={() => onEdit(tItem)}
                className="text-cyan-500 hover:text-cyan-400 p-1.5 rounded-lg hover:bg-cyan-500/10 transition-colors"
                title={t.track.editTrack}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </button>
              <button
                type="button"
                onClick={() => onDelete(tItem.id, tItem.name)}
                className="text-rose-500 hover:text-rose-400 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors"
                title="Delete Track"
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
