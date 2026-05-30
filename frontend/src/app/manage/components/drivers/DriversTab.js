"use client";

import { useState } from "react";
import DriversList from "./DriversList";
import DriverFormModal from "./DriverFormModal";
import { useTranslation } from "@/context/LanguageContext";

export default function DriversTab({ drivers, onCreate, onUpdate, onDelete }) {
  const { t, lang } = useTranslation();
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingDriver, setEditingDriver] = useState(null);

  const handleStartEdit = (driver) => {
    setEditingDriver(driver);
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setEditingDriver(null);
    setIsModalOpen(false);
  };

  const handleFormSubmit = async (formData) => {
    if (editingDriver) {
      await onUpdate(editingDriver.id, formData);
    } else {
      await onCreate(formData);
    }
  };

  return (
    <div className="space-y-6">
      {/* Action Bar */}
      <div className="glass-panel p-4 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-white/5 bg-slate-900/40">
        <div className="flex items-center gap-3 w-full sm:w-auto">
          <div className="px-3 py-1.5 rounded-xl bg-slate-955 border border-white/5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
            {t.database.quickFilters}
          </div>
        </div>
        <button
          type="button"
          onClick={() => {
            setEditingDriver(null);
            setIsModalOpen(true);
          }}
          className="w-full sm:w-auto px-5 py-3 rounded-xl text-xs font-extrabold bg-cyan-500 hover:bg-cyan-400 text-black uppercase tracking-wider transition-all active:scale-[0.98] shadow-md shadow-cyan-500/10 flex items-center justify-center gap-2"
        >
          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2.5">
            <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          {t.database.addDriver}
        </button>
      </div>

      {/* Grid List */}
      <div className="w-full">
        <DriversList 
          drivers={drivers} 
          onEdit={handleStartEdit} 
          onDelete={onDelete} 
        />
      </div>

      {/* Form Modal */}
      <DriverFormModal 
        isOpen={isModalOpen} 
        onClose={handleCloseModal} 
        onSubmit={handleFormSubmit} 
        editingDriver={editingDriver} 
      />
    </div>
  );
}
