"use client";

import { createContext, useContext, useState, useEffect } from "react";
import { virtualDb } from "@/services/api";

const AuthContext = createContext({
  user: null,
  loading: true,
  login: () => {},
  logout: () => {},
});

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Read session from localStorage on mount
    try {
      const savedUser = localStorage.getItem("slotrace_user");
      if (savedUser) {
        setUser(JSON.parse(savedUser));
      }
    } catch (err) {
      console.error("Erro ao carregar usuário autenticado:", err);
    } finally {
      setLoading(false);
    }
  }, []);

  const login = (profileData) => {
    try {
      // Clear token leftovers in sessionStorage before logging in new user
      if (typeof window !== "undefined") {
        sessionStorage.removeItem("slotrace_drive_token");
        for (let i = sessionStorage.length - 1; i >= 0; i--) {
          const key = sessionStorage.key(i);
          if (key && key.startsWith("slotrace_")) {
            sessionStorage.removeItem(key);
          }
        }
      }
      
      localStorage.setItem("slotrace_user", JSON.stringify(profileData));
      setUser(profileData);
      
      // Initialize database cache immediately for the new user session
      virtualDb.initSession();
    } catch (err) {
      console.error("Erro ao salvar sessão de usuário:", err);
    }
  };

  const logout = () => {
    try {
      // Clean memory database cache immediately
      virtualDb.clearSession();
      
      // Remove session storage keys
      if (typeof window !== "undefined") {
        sessionStorage.removeItem("slotrace_drive_token");
        for (let i = sessionStorage.length - 1; i >= 0; i--) {
          const key = sessionStorage.key(i);
          if (key && key.startsWith("slotrace_")) {
            sessionStorage.removeItem(key);
          }
        }
      }
      
      localStorage.removeItem("slotrace_user");
      setUser(null);
    } catch (err) {
      console.error("Erro ao remover sessão de usuário:", err);
    }
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
