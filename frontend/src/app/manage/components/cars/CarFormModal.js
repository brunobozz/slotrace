"use client";

import { useState, useEffect, useRef } from "react";
import ImageCropperModal from "../shared/ImageCropperModal";
import { useTranslation } from "@/context/LanguageContext";

export default function CarFormModal({ isOpen, onClose, onSubmit, editingCar, drivers }) {
  const { t, lang } = useTranslation();
  const [form, setForm] = useState({ name: "", brand: "", model: "", scale: "1:32", driver_id: "" });
  const [selectedFile, setSelectedFile] = useState(null);
  const [previewUrl, setPreviewUrl] = useState(null);
  const [imageDeleted, setImageDeleted] = useState(false);
  const [rawCroppingFile, setRawCroppingFile] = useState(null);
  const [submitting, setSubmitting] = useState(false);

  const fileInputRef = useRef(null);

  useEffect(() => {
    if (editingCar) {
      setForm({
        name: editingCar.name || "",
        brand: editingCar.brand || "",
        model: editingCar.model || "",
        scale: editingCar.scale || "1:32",
        driver_id: editingCar.driver_id || "",
      });
    } else {
      setForm({ name: "", brand: "", model: "", scale: "1:32", driver_id: "" });
    }
    setSelectedFile(null);
    setRawCroppingFile(null);
    setImageDeleted(false);
  }, [editingCar, isOpen]);

  // Handle local memory URL for previewing newly cropped file
  useEffect(() => {
    if (selectedFile) {
      const url = URL.createObjectURL(selectedFile);
      setPreviewUrl(url);
      return () => URL.revokeObjectURL(url);
    } else {
      setPreviewUrl(null);
    }
  }, [selectedFile]);

  if (!isOpen) return null;

  const handleDeleteImage = () => {
    setSelectedFile(null);
    setImageDeleted(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.name.trim()) return;
    try {
      setSubmitting(true);
      
      const formData = new FormData();
      formData.append("name", form.name);
      if (form.brand) formData.append("brand", form.brand);
      if (form.model) formData.append("model", form.model);
      if (form.scale) formData.append("scale", form.scale);
      
      formData.append("driver_id", form.driver_id || "");

      if (imageDeleted && !selectedFile) {
        formData.append("image", "");
      } else if (selectedFile) {
        formData.append("image", selectedFile);
      }

      await onSubmit(formData);
      onClose();
    } catch (err) {
      alert(`Error saving car: ` + err.message);
    } finally {
      setSubmitting(false);
    }
  };

  const hasImage = previewUrl || (editingCar?.image_url && !imageDeleted);

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      {/* Backdrop */}
      <div 
        className="fixed inset-0 bg-slate-955/60 backdrop-blur-md transition-opacity duration-300"
        onClick={onClose}
      />

      {/* Modal Dialog */}
      <div className="relative w-full max-w-md glass-panel p-6 rounded-2xl border border-white/10 bg-slate-900/90 shadow-2xl z-10 space-y-6 animate-in fade-in zoom-in-95 duration-200">
        <div className="flex items-center justify-between border-b border-white/5 pb-3">
          <h2 className="text-lg font-bold text-slate-200">
            {editingCar ? t.car.editCar : t.car.registerCar}
          </h2>
          <button 
            type="button"
            onClick={onClose}
            className="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-white/5 border border-white/5 transition-all"
            title={t.driver.close}
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="space-y-1.5">
            <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">{t.car.carName}</label>
            <input
              type="text"
              required
              placeholder="e.g. McLaren MP4/4"
              value={form.name}
              onChange={(e) => setForm({ ...form, name: e.target.value })}
              className="w-full px-3 py-2.5 rounded-xl bg-slate-955 border border-white/5 text-slate-200 text-sm focus:outline-none focus:border-cyan-500/50 transition-colors"
            />
          </div>
          
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-1.5">
              <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">{t.car.manufacturer}</label>
              <input
                type="text"
                placeholder="Slot.it, Carrera"
                value={form.brand}
                onChange={(e) => setForm({ ...form, brand: e.target.value })}
                className="w-full px-3 py-2.5 rounded-xl bg-slate-955 border border-white/5 text-slate-200 text-sm focus:outline-none focus:border-cyan-500/50 transition-colors"
              />
            </div>
            <div className="space-y-1.5">
              <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">{t.car.scale}</label>
              <input
                type="text"
                value={form.scale}
                onChange={(e) => setForm({ ...form, scale: e.target.value })}
                className="w-full px-3 py-2.5 rounded-xl bg-slate-955 border border-white/5 text-slate-200 text-sm focus:outline-none focus:border-cyan-500/50 transition-colors"
              />
            </div>
          </div>

          <div className="space-y-1.5">
            <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">{t.car.ownerOptional}</label>
            <select
              value={form.driver_id}
              onChange={(e) => setForm({ ...form, driver_id: e.target.value })}
              className="w-full px-3 py-2.5 rounded-xl bg-slate-955 border border-white/5 text-slate-350 text-sm focus:outline-none focus:border-cyan-500/50 transition-colors"
            >
              <option value="">{t.car.noOwner}</option>
              {drivers.map(d => (
                <option key={d.id} value={d.id}>{d.name} ({d.nickname || t.car.noNickname})</option>
              ))}
            </select>
          </div>

          <div className="space-y-3">
            <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">
              {editingCar ? t.car.photoLabelKeep : t.car.photoLabelNew}
            </label>

            {/* Premium 16:9 Image Preview Card with Action Overlays */}
            {hasImage ? (
              <div className="flex items-center justify-between p-2.5 rounded-xl bg-slate-955/40 border border-white/5 shadow-inner">
                <div className="flex items-center gap-3.5">
                  <img 
                    src={previewUrl || editingCar.image_url} 
                    alt="Car Preview" 
                    className="h-20 aspect-video rounded-lg object-cover border border-white/10 bg-slate-900/60 shadow-md"
                  />
                  {(previewUrl || editingCar?.image_size_formatted) && (
                    <span className="text-xs font-mono-telemetry font-bold text-slate-400 bg-slate-955/60 border border-white/5 px-2 py-0.5 rounded-lg">
                      {previewUrl ? `${(selectedFile.size / 1024).toFixed(0)} KB` : editingCar.image_size_formatted}
                    </span>
                  )}
                </div>
                
                {/* Lapis (edit) and Lixeira (delete) Buttons inside modal card */}
                <div className="flex items-center gap-1.5">
                  <button
                    type="button"
                    onClick={() => fileInputRef.current.click()}
                    className="text-cyan-500 hover:text-cyan-400 p-1.5 rounded-lg hover:bg-cyan-500/10 transition-colors"
                    title={t.car.changeImg}
                  >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2.5">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                  </button>
                  <button
                    type="button"
                    onClick={handleDeleteImage}
                    className="text-rose-500 hover:text-rose-400 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors"
                    title={t.car.deleteImg}
                  >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2.5">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            ) : (
              /* If no image exists, show the standard file input styled */
              <input
                ref={fileInputRef}
                type="file"
                accept="image/*"
                onChange={(e) => {
                  if (e.target.files && e.target.files[0]) {
                    setRawCroppingFile(e.target.files[0]);
                  }
                }}
                className="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white/5 file:text-slate-200 hover:file:bg-white/10 file:cursor-pointer cursor-pointer focus:outline-none"
              />
            )}

            {/* Hidden Input to handle Click trigger when image is shown */}
            {hasImage && (
              <input
                ref={fileInputRef}
                type="file"
                accept="image/*"
                onChange={(e) => {
                  if (e.target.files && e.target.files[0]) {
                    setRawCroppingFile(e.target.files[0]);
                  }
                }}
                className="hidden"
              />
            )}
          </div>

          <div className="flex gap-3 pt-3">
            <button
              type="button"
              onClick={onClose}
              className="flex-1 py-3 rounded-xl text-xs font-extrabold bg-white/5 hover:bg-white/10 text-slate-300 uppercase tracking-wider transition-all active:scale-[0.98]"
            >
              {t.car.cancel}
            </button>
            <button
              type="submit"
              disabled={submitting}
              className="flex-1 py-3 rounded-xl text-xs font-extrabold bg-cyan-500 hover:bg-cyan-400 text-black uppercase tracking-wider transition-all disabled:opacity-50 active:scale-[0.98]"
            >
              {submitting 
                ? (editingCar ? t.car.saving : t.car.registering) 
                : (editingCar ? t.car.save : t.car.register)}
            </button>
          </div>
        </form>
      </div>

      {/* Floating Image Cropper */}
      <ImageCropperModal
        file={rawCroppingFile}
        aspectRatio="16:9"
        onConfirm={(croppedFile) => {
          setSelectedFile(croppedFile);
          setRawCroppingFile(null);
        }}
        onCancel={() => setRawCroppingFile(null)}
      />
    </div>
  );
}
