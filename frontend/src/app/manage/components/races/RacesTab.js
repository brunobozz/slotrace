"use client";

import RacesList from "./RacesList";
import { useTranslation } from "@/context/LanguageContext";

export default function RacesTab({ races, onDelete }) {
  const { t, lang } = useTranslation();

  return (
    <div className="space-y-6">
      {/* Action Bar */}
      <div className="glass-panel p-4 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-white/5 bg-slate-900/40">
        <div className="flex items-center gap-3 w-full sm:w-auto">
          <div className="px-3 py-1.5 rounded-xl bg-slate-955 border border-white/5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
            {t.database.quickFilters}
          </div>
        </div>
        <a
          href="/races/create"
          className="w-full sm:w-auto px-5 py-3 rounded-xl text-xs font-extrabold bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-black uppercase tracking-wider transition-all active:scale-[0.98] shadow-md shadow-cyan-500/10 flex items-center justify-center gap-2"
        >
          {t.database.newGp} 🏁
        </a>
      </div>

      {/* Grid List */}
      <div className="w-full">
        <RacesList 
          races={races} 
          onDelete={onDelete} 
        />
      </div>
    </div>
  );
}
