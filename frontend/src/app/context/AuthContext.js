"use client";

import { createContext, useContext, useState, useEffect } from "react";

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
      localStorage.setItem("slotrace_user", JSON.stringify(profileData));
      setUser(profileData);
    } catch (err) {
      console.error("Erro ao salvar sessão de usuário:", err);
    }
  };

  const logout = () => {
    try {
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
