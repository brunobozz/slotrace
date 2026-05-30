"use client";

import { useAuth } from "../context/AuthContext";
import LoginScreen from "./LoginScreen";
import Link from "next/link";
import { usePathname } from "next/navigation";
import GoogleDriveSync from "./GoogleDriveSync";
import { useTranslation } from "@/context/LanguageContext";

export default function AuthWrapper({ children }) {
  const { user, loading, logout } = useAuth();
  const pathname = usePathname();
  const { t, lang, changeLanguage } = useTranslation();

  // 1. Loading state (glowing neon dashboard loading screen)
  if (loading) {
    return (
      <div className="fixed inset-0 z-50 flex items-center justify-center bg-[#05070c] font-mono-telemetry text-xs text-slate-500 select-none">
        <div className="flex flex-col items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-rose-500 flex items-center justify-center animate-spin duration-3000 shadow-lg shadow-cyan-500/20">
            <svg className="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2.5">
              <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>
          </div>
          <div className="flex flex-col items-center gap-1 mt-2 text-center">
            <span className="font-extrabold text-[10px] tracking-[0.2em] text-cyan-400 uppercase">SLOTRACE OS v1.0</span>
            <span className="text-[9px] opacity-60">LOADING TELEMETRY SYSTEM...</span>
          </div>
        </div>
      </div>
    );
  }

  // 2. Not Authenticated -> Show absolute login page
  if (!user) {
    return <LoginScreen />;
  }

  // Helper to determine active link styling
  const isActive = (path) => pathname === path;

  // 3. Authenticated -> Show premium layout shell with navigation
  return (
    <>
      {/* Header / Nav */}
      <header className="sticky top-0 z-50 w-full border-b border-white/5 bg-[#090b11]/80 backdrop-blur-md">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          
          {/* Logo */}
          <Link href="/" className="flex items-center gap-3 group">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-rose-500 flex items-center justify-center shadow-lg shadow-cyan-500/20 group-hover:scale-105 transition-transform duration-300">
              <svg className="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2.5">
                <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
              </svg>
            </div>
            <div className="flex flex-col">
              <span className="font-extrabold text-xl tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-rose-400 group-hover:from-cyan-300 group-hover:to-rose-300 transition-all duration-300">
                SLOTRACE
              </span>
              <span className="text-[10px] text-slate-400 font-medium tracking-widest -mt-1 uppercase">
                Telemetry System
              </span>
            </div>
          </Link>

          {/* Navigation and User Badge */}
          <div className="flex items-center gap-4">
            <nav className="flex items-center gap-1 sm:gap-3">
              <Link 
                href="/" 
                className={`px-3 py-2 rounded-lg text-sm font-semibold transition-all ${isActive("/") ? "bg-white/5 text-cyan-400" : "text-slate-300 hover:text-white hover:bg-white/5"}`}
              >
                {t.nav.dashboard}
              </Link>
              <Link 
                href="/manage" 
                className={`px-3 py-2 rounded-lg text-sm font-semibold transition-all ${isActive("/manage") ? "bg-white/5 text-cyan-400" : "text-slate-300 hover:text-white hover:bg-white/5"}`}
              >
                {t.nav.database}
              </Link>
              <Link 
                href="/races/create" 
                className={`px-3.5 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-black shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 transition-all ${isActive("/races/create") ? "ring-2 ring-cyan-500/50" : ""}`}
              >
                {t.nav.newGp}
              </Link>
            </nav>

            {/* Premium Google Accounts User Badge */}
            <div className="flex items-center gap-2.5 pl-3 sm:pl-4 border-l border-white/10">
              <GoogleDriveSync />
              
              {/* Flag Selection Dropdown */}
              <div className="relative group/lang select-none mr-1">
                <button className="flex items-center justify-center w-8 h-8 rounded-full border border-white/5 bg-slate-900 hover:bg-white/5 active:scale-95 transition-all relative overflow-hidden">
                  <img 
                    src={lang === "pt" ? "https://flagcdn.com/w40/br.png" : lang === "es" ? "https://flagcdn.com/w40/es.png" : "https://flagcdn.com/w40/us.png"} 
                    alt={lang}
                    className="w-full h-full object-cover rounded-full"
                  />
                </button>
                {/* Dropdown Menu (on hover) */}
                <div className="absolute right-0 top-full mt-2 w-32 glass-panel p-1 rounded-xl border border-white/10 bg-slate-900/90 shadow-2xl opacity-0 scale-95 pointer-events-none group-hover/lang:opacity-100 group-hover/lang:scale-100 group-hover/lang:pointer-events-auto transition-all duration-200 z-[100] flex flex-col gap-0.5">
                  <button 
                    onClick={() => changeLanguage("en")}
                    className={`flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-semibold transition-all hover:bg-white/5 text-left ${lang === "en" ? "text-cyan-400 bg-cyan-500/5" : "text-slate-300"}`}
                  >
                    <img src="https://flagcdn.com/w40/us.png" className="w-5 h-5 rounded-full object-cover border border-white/10" alt="English" />
                    English
                  </button>
                  <button 
                    onClick={() => changeLanguage("pt")}
                    className={`flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-semibold transition-all hover:bg-white/5 text-left ${lang === "pt" ? "text-cyan-400 bg-cyan-500/5" : "text-slate-300"}`}
                  >
                    <img src="https://flagcdn.com/w40/br.png" className="w-5 h-5 rounded-full object-cover border border-white/10" alt="Português" />
                    Português
                  </button>
                  <button 
                    onClick={() => changeLanguage("es")}
                    className={`flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-semibold transition-all hover:bg-white/5 text-left ${lang === "es" ? "text-cyan-400 bg-cyan-500/5" : "text-slate-300"}`}
                  >
                    <img src="https://flagcdn.com/w40/es.png" className="w-5 h-5 rounded-full object-cover border border-white/10" alt="Español" />
                    Español
                  </button>
                </div>
              </div>

              <img 
                src={user.avatar_url} 
                alt={user.name} 
                className="w-8 h-8 rounded-full object-cover border border-cyan-500/40 shadow-inner bg-slate-900"
                title={user.email}
              />
              <div className="hidden md:flex flex-col text-left max-w-[120px]">
                <span className="text-xs font-black text-slate-200 truncate leading-tight">{user.name}</span>
                <span className="text-[9px] text-slate-500 font-mono-telemetry truncate">{user.email}</span>
              </div>
              <button
                onClick={logout}
                className="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-rose-500/10 border border-white/5 hover:border-rose-500/20 transition-all ml-1.5"
                title={t.nav.signOut}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2.5">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
              </button>
            </div>
          </div>

        </div>
      </header>

      {/* Main Content */}
      <main className="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {children}
      </main>

      {/* Footer */}
      <footer className="w-full border-t border-white/5 bg-slate-955/45 py-6 mt-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
          <div>
            &copy; {new Date().getFullYear()} Slotrace Telemetry. All rights reserved.
          </div>
          <div className="flex items-center gap-4">
            <span className="flex items-center gap-1.5">
              Authentication: <span className="text-cyan-400 font-semibold uppercase">{user.auth_type?.replace("google_", "") || "GOOGLE"}</span>
            </span>
            <span className="w-1.5 h-1.5 rounded-full bg-slate-700" />
            <span>API Status: <span className="text-emerald-400 font-semibold">Online</span></span>
          </div>
        </div>
      </footer>
    </>
  );
}
