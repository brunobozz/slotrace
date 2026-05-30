"use client";

import { useEffect, useState } from "react";
import { api } from "@/services/api";
import { useAuth } from "../context/AuthContext";
import { useTranslation } from "@/context/LanguageContext";
import DriversTab from "./components/drivers/DriversTab";
import CarsTab from "./components/cars/CarsTab";
import TracksTab from "./components/tracks/TracksTab";
import RacesTab from "./components/races/RacesTab";

export default function ManageSystem() {
  const { t, lang } = useTranslation();
  const { user } = useAuth();
  const [activeTab, setActiveTab] = useState("drivers");
  const [drivers, setDrivers] = useState([]);
  const [cars, setCars] = useState([]);
  const [tracks, setTracks] = useState([]);
  const [races, setRaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isAuthorized, setIsAuthorized] = useState(false);

  const checkAuth = () => {
    if (typeof window === "undefined") return;
    const token = sessionStorage.getItem("slotrace_drive_token");
    const isSim = user?.auth_type?.includes("simulated");
    setIsAuthorized(!!token || isSim);
  };

  useEffect(() => {
    checkAuth();
    window.addEventListener("slotrace_token_updated", checkAuth);
    return () => window.removeEventListener("slotrace_token_updated", checkAuth);
  }, [user]);

  const fetchAllData = async () => {
    const token = sessionStorage.getItem("slotrace_drive_token");
    const isSim = user?.auth_type?.includes("simulated");
    if (!token && !isSim) {
      setLoading(false);
      return;
    }
    
    try {
      setLoading(true);
      const [driversData, carsData, tracksData, racesData] = await Promise.all([
        api.drivers.list().catch(() => []),
        api.cars.list().catch(() => []),
        api.tracks.list().catch(() => []),
        api.races.list().catch(() => []),
      ]);
      setDrivers(driversData);
      setCars(carsData);
      setTracks(tracksData);
      setRaces(racesData);
      setError(null);
    } catch (err) {
      console.error(err);
      setError("Error loading registration data from the API.");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (isAuthorized) {
      fetchAllData();
    } else {
      setLoading(false);
    }
  }, [isAuthorized]);

  useEffect(() => {
    const handleRestore = () => {
      fetchAllData();
    };

    window.addEventListener("slotrace_data_restored", handleRestore);
    return () => window.removeEventListener("slotrace_data_restored", handleRestore);
  }, []);

  const handleCreateDriver = async (driverData) => {
    await api.drivers.create(driverData);
    await fetchAllData();
  };

  const handleUpdateDriver = async (id, driverData) => {
    await api.drivers.update(id, driverData);
    await fetchAllData();
  };

  const handleDeleteDriver = async (id, name) => {
    if (!confirm(`Do you really want to delete the driver "${name}"? This action will remove the driver and all associated records.`)) return;
    try {
      await api.drivers.delete(id);
      await fetchAllData();
    } catch (err) {
      alert("Error deleting driver: " + err.message);
    }
  };

  const handleCreateCar = async (carData) => {
    await api.cars.create(carData);
    await fetchAllData();
  };

  const handleUpdateCar = async (id, carData) => {
    await api.cars.update(id, carData);
    await fetchAllData();
  };

  const handleDeleteCar = async (id, name) => {
    if (!confirm(`Do you really want to delete the car "${name}"? This action will permanently remove the car and its associations.`)) return;
    try {
      await api.cars.delete(id);
      await fetchAllData();
    } catch (err) {
      alert("Error deleting car: " + err.message);
    }
  };

  const handleCreateTrack = async (trackData) => {
    await api.tracks.create(trackData);
    await fetchAllData();
  };

  const handleUpdateTrack = async (id, trackData) => {
    await api.tracks.update(id, trackData);
    await fetchAllData();
  };

  const handleDeleteTrack = async (id, name) => {
    if (!confirm(`Do you really want to delete the track "${name}"? This action will remove the track and all of its associated record and race history.`)) return;
    try {
      await api.tracks.delete(id);
      await fetchAllData();
    } catch (err) {
      alert("Error deleting track: " + err.message);
    }
  };

  const handleDeleteRace = async (id, name) => {
    if (!confirm(`Do you really want to delete the race "${name}"? This action will permanently remove the race and all of its time and lap history.`)) return;
    try {
      await api.races.delete(id);
      await fetchAllData();
    } catch (err) {
      alert("Error deleting race: " + err.message);
    }
  };

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[50vh] gap-4">
        <div className="w-12 h-12 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
        <span className="text-slate-400 font-semibold tracking-wider animate-pulse">{t.telemetry.receivers}</span>
      </div>
    );
  }

  if (!isAuthorized) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[60vh] text-center p-8 glass-panel max-w-md mx-auto space-y-6 rounded-3xl border border-white/10 mt-10 animate-in fade-in zoom-in-95 duration-200">
        <div className="w-16 h-16 mx-auto rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
          <svg className="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <div className="space-y-2">
          <h3 className="text-lg font-bold text-slate-200">{t.database.restrictedTitle}</h3>
          <p className="text-xs text-slate-400 leading-relaxed">
            {t.database.restrictedDesc}
          </p>
        </div>
        <button
          onClick={() => window.dispatchEvent(new Event("slotrace_open_sync"))}
          className="px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-extrabold uppercase text-[10px] tracking-wider rounded-xl shadow-lg shadow-cyan-500/10 transition-all active:scale-95"
        >
          {t.database.connectBtn}
        </button>
      </div>
    );
  }

  const tabs = [
    { id: "drivers", label: t.database.tabDrivers, count: drivers.length },
    { id: "cars", label: t.database.tabCars, count: cars.length },
    { id: "tracks", label: t.database.tabTracks, count: tracks.length },
    { id: "races", label: t.database.tabRaces, count: races.length },
  ];

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="space-y-2">
        <h1 className="text-3xl font-extrabold tracking-tight text-white">{t.database.title}</h1>
        <p className="text-slate-400 text-sm">
          {t.database.subtitle}
        </p>
      </div>

      {error && (
        <div className="p-4 rounded-xl border border-rose-500/30 bg-rose-500/10 text-rose-300 text-sm flex items-center gap-3">
          <svg className="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          {error}
        </div>
      )}

      {/* Tabs Menu */}
      <div className="flex border-b border-white/5 gap-2">
        {tabs.map((tab) => (
          <button
            key={tab.id}
            onClick={() => setActiveTab(tab.id)}
            className={`px-6 py-3 text-sm font-bold border-b-2 transition-all flex items-center gap-2 ${
              activeTab === tab.id
                ? "border-cyan-500 text-cyan-400 bg-cyan-500/5"
                : "border-transparent text-slate-400 hover:text-slate-200"
            }`}
          >
            {tab.label}
            <span className="text-[10px] bg-white/5 border border-white/10 px-1.5 py-0.5 rounded-full text-slate-300">
              {tab.count}
            </span>
          </button>
        ))}
      </div>

      {/* Active Tab View */}
      <div className="mt-6">
        {activeTab === "drivers" && (
          <DriversTab drivers={drivers} onCreate={handleCreateDriver} onUpdate={handleUpdateDriver} onDelete={handleDeleteDriver} />
        )}
        {activeTab === "cars" && (
          <CarsTab cars={cars} drivers={drivers} onCreate={handleCreateCar} onUpdate={handleUpdateCar} onDelete={handleDeleteCar} />
        )}
        {activeTab === "tracks" && (
          <TracksTab tracks={tracks} onCreate={handleCreateTrack} onUpdate={handleUpdateTrack} onDelete={handleDeleteTrack} />
        )}
        {activeTab === "races" && (
          <RacesTab races={races} onDelete={handleDeleteRace} />
        )}
      </div>
    </div>
  );
}
