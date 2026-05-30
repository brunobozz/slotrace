"use client";

import { useState, useEffect, useRef } from "react";
import ImageCropperModal from "../shared/ImageCropperModal";
import { useTranslation } from "@/context/LanguageContext";

export default function DriverFormModal({ isOpen, onClose, onSubmit, editingDriver }) {
  const { t, lang } = useTranslation();
  const [form, setForm] = useState({ name: "", nickname: "" });
  const [selectedFile, setSelectedFile] = useState(null);
  const [previewUrl, setPreviewUrl] = useState(null);
  const [avatarDeleted, setAvatarDeleted] = useState(false);
  const [rawCroppingFile, setRawCroppingFile] = useState(null);
  const [submitting, setSubmitting] = useState(false);
  
  const fileInputRef = useRef(null);

  useEffect(() => {
    if (editingDriver) {
      setForm({
        name: editingDriver.name || "",
        nickname: editingDriver.nickname || "",
      });
    } else {
      setForm({ name: "", nickname: "" });
    }
    setSelectedFile(null);
    setRawCroppingFile(null);
    setAvatarDeleted(false);
  }, [editingDriver, isOpen]);

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
    setAvatarDeleted(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.name.trim()) return;
    try {
      setSubmitting(true);
      const formData = new FormData();
      formData.append("name", form.name);
      if (form.nickname) {
        formData.append("nickname", form.nickname);
      } else {
        formData.append("nickname", "");
      }
      
      if (avatarDeleted && !selectedFile) {
        formData.append("avatar", "");
      } else if (selectedFile) {
        formData.append("avatar", selectedFile);
      }
      
      await onSubmit(formData);
      onClose();
    } catch (err) {
      alert(`Error saving driver: ` + err.message);
    } finally {
      setSubmitting(false);
    }
  };

  const hasImage = previewUrl || (editingDriver?.avatar_url && !avatarDeleted);

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
            {editingDriver ? t.driver.editDriver : t.driver.registerDriver}
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
            <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">{t.driver.driverName}</label>
            <input
              type="text"
              required
              placeholder="e.g. Ayrton Senna"
              value={form.name}
              onChange={(e) => setForm({ ...form, name: e.target.value })}
              className="w-full px-3 py-2.5 rounded-xl bg-slate-955 border border-white/5 text-slate-200 text-sm focus:outline-none focus:border-cyan-500/50 transition-colors"
            />
          </div>
          
          <div className="space-y-1.5">
            <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">{t.driver.nicknameLabel}</label>
            <input
              type="text"
              placeholder="e.g. Senna"
              value={form.nickname}
              onChange={(e) => setForm({ ...form, nickname: e.target.value })}
              className="w-full px-3 py-2.5 rounded-xl bg-slate-955 border border-white/5 text-slate-200 text-sm focus:outline-none focus:border-cyan-500/50 transition-colors"
            />
          </div>

          <div className="space-y-3">
            <label className="text-[10px] text-slate-500 font-bold uppercase tracking-wider block">
              {editingDriver ? t.driver.photoLabelKeep : t.driver.photoLabelNew}
            </label>

            {/* Premium Circular Image Preview Card with Action Overlays */}
            {hasImage ? (
              <div className="flex items-center justify-between p-2.5 rounded-xl bg-slate-955/40 border border-white/5 shadow-inner">
                <div className="flex items-center gap-3">
                  <img 
                    src={previewUrl || editingDriver.avatar_url} 
                    alt="Avatar Preview" 
                    className="w-20 h-20 rounded-full object-cover border border-white/10 bg-slate-900/60 shadow-md"
                  />
                  {(previewUrl || editingDriver?.avatar_size_formatted) && (
                    <span className="text-xs font-mono-telemetry font-bold text-slate-400 bg-slate-955/60 border border-white/5 px-2 py-0.5 rounded-lg">
                      {previewUrl ? `${(selectedFile.size / 1024).toFixed(0)} KB` : editingDriver.avatar_size_formatted}
                    </span>
                  )}
                </div>
                
                {/* Lapis (edit) and Lixeira (delete) Buttons inside modal card */}
                <div className="flex items-center gap-1.5">
                  <button
                    type="button"
                    onClick={() => fileInputRef.current.click()}
                    className="text-cyan-500 hover:text-cyan-400 p-1.5 rounded-lg hover:bg-cyan-500/10 transition-colors"
                    title={t.driver.changeImg}
                  >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2.5">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                  </button>
                  <button
                    type="button"
                    onClick={handleDeleteImage}
                    className="text-rose-500 hover:text-rose-400 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors"
                    title={t.driver.deleteImg}
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
              {t.driver.cancel}
            </button>
            <button
              type="submit"
              disabled={submitting}
              className="flex-1 py-3 rounded-xl text-xs font-extrabold bg-cyan-500 hover:bg-cyan-400 text-black uppercase tracking-wider transition-all disabled:opacity-50 active:scale-[0.98]"
            >
              {submitting 
                ? (editingDriver ? t.driver.saving : t.driver.registering) 
                : (editingDriver ? t.driver.save : t.driver.register)}
            </button>
          </div>
        </form>
      </div>

      {/* Floating Image Cropper */}
      <ImageCropperModal
        file={rawCroppingFile}
        aspectRatio="1:1"
        onConfirm={(croppedFile) => {
          setSelectedFile(croppedFile);
          setRawCroppingFile(null);
        }}
        onCancel={() => setRawCroppingFile(null)}
      />
    </div>
  );
}
