"use client";

import React, { createContext, useContext, useState, useEffect } from "react";
import { en } from "../locales/en";
import { pt } from "../locales/pt";
import { es } from "../locales/es";
import { api } from "@/services/api";
import { useAuth } from "@/app/context/AuthContext";

const LanguageContext = createContext();

export function LanguageProvider({ children }) {
  const [lang, setLang] = useState("en");
  const { user } = useAuth();
  const email = user?.email || "guest";

  // On mount or when user changes, load from user-specific localStorage instantly
  useEffect(() => {
    if (typeof window !== "undefined") {
      const saved = localStorage.getItem(`slotrace_lang_${email}`);
      if (saved) {
        setLang(saved);
      } else {
        setLang("en");
      }
    }
  }, [email]);

  // Listen to virtual DB restoration from Google Drive to sync dynamic settings
  useEffect(() => {
    const syncRestoredLanguage = async () => {
      try {
        const settings = await api.settings.get();
        if (settings && settings.lang && settings.lang !== lang) {
          setLang(settings.lang);
          localStorage.setItem(`slotrace_lang_${email}`, settings.lang);
        }
      } catch (err) {
        console.warn("Failed to sync restored language settings:", err);
      }
    };

    window.addEventListener("slotrace_data_restored", syncRestoredLanguage);
    return () => window.removeEventListener("slotrace_data_restored", syncRestoredLanguage);
  }, [lang, email]);

  const changeLanguage = async (newLang) => {
    setLang(newLang);
    if (typeof window !== "undefined") {
      localStorage.setItem(`slotrace_lang_${email}`, newLang);
      
      // Update in the Virtual Database Cache to automatically backup to Google Drive
      try {
        await api.settings.setLang(newLang);
      } catch (err) {
        console.warn("Failed to persist language setting to Virtual DB:", err);
      }
    }
  };

  const getDictionary = () => {
    switch (lang) {
      case "pt":
        return pt;
      case "es":
        return es;
      default:
        return en;
    }
  };

  return (
    <LanguageContext.Provider value={{ lang, changeLanguage, t: getDictionary() }}>
      {children}
    </LanguageContext.Provider>
  );
}

export const useTranslation = () => {
  const context = useContext(LanguageContext);
  if (!context) {
    throw new Error("useTranslation must be used within a LanguageProvider");
  }
  return context;
};
