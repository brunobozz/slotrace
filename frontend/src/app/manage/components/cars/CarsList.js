"use client";

import { useTranslation } from "@/context/LanguageContext";

export default function CarsList({ cars, onEdit, onDelete }) {
  const { t, lang } = useTranslation();

  if (cars.length === 0) {
    return (
      <div className="glass-panel p-8 text-center text-slate-500 text-sm rounded-2xl border border-white/5">
        {t.database.noCars}
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {cars.map((c) => (
        <div 
          key={c.id} 
          className="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-white/10 transition-colors flex flex-col justify-between"
        >
          <div>
            {c.image_url ? (
              <div className="w-full h-36 rounded-xl overflow-hidden mb-4 border border-white/5 bg-slate-955/60 relative">
                <img src={c.image_url} alt={c.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
              </div>
            ) : (
              <div className="w-full h-36 rounded-xl mb-4 border border-white/5 bg-slate-955/40 relative flex flex-col items-center justify-center text-slate-600 group-hover:text-cyan-500/50 transition-colors">
                <svg className="w-10 h-10 mb-2 opacity-30 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="1.5">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.321-5.128a2.25 2.25 0 0 0-2.25-2.112h-9.873a2.25 2.25 0 0 0-2.25 2.112l-.321 5.128c-.039.62.469 1.124 1.09 1.124H15" />
                </svg>
                <span className="text-[10px] uppercase font-bold tracking-widest opacity-40">{t.car.noPhoto}</span>
              </div>
            )}
            
            <div className="flex items-start justify-between">
              <div>
                <span className="text-[10px] text-slate-500 font-bold uppercase tracking-widest block">{t.car.car}</span>
                <h3 className="text-base font-black text-slate-200 mt-1 leading-tight">{c.name}</h3>
              </div>
              <span className="text-[10px] font-bold text-slate-400 bg-white/5 border border-white/10 px-2 py-0.5 rounded">
                {c.scale}
              </span>
            </div>
          </div>
          
          <div className="flex items-end justify-between border-t border-white/5 mt-3 pt-3">
            <div className="flex flex-col gap-1 text-xs text-slate-400">
              {c.brand && <span>{t.car.manufacturer}: <strong className="text-slate-300 font-semibold">{c.brand}</strong></span>}
              {c.model && <span>{t.car.model}: <strong className="text-slate-300 font-semibold">{c.model}</strong></span>}
              <span>{t.car.owner}: <strong className="text-cyan-400 font-bold">{c.driver ? c.driver.name : t.car.generalCollection}</strong></span>
            </div>
            
            <div className="flex items-center gap-1.5 self-end">
              <button
                type="button"
                onClick={() => onEdit(c)}
                className="text-cyan-500 hover:text-cyan-400 p-1.5 rounded-lg hover:bg-cyan-500/10 transition-colors"
                title={t.car.editCar}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </button>
              <button
                type="button"
                onClick={() => onDelete(c.id, c.name)}
                className="text-rose-500 hover:text-rose-400 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors"
                title="Delete Car"
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
