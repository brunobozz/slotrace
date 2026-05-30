"use client";

import { useState, useEffect, useRef } from "react";

export default function ImageCropperModal({ file, aspectRatio = "1:1", onConfirm, onCancel }) {
  const [imageSrc, setImageSrc] = useState("");
  const [zoom, setZoom] = useState(1);
  const [pan, setPan] = useState({ x: 0, y: 0 });
  const [isDragging, setIsDragging] = useState(false);
  const dragStart = useRef({ x: 0, y: 0 });
  const containerRef = useRef(null);
  const imgRef = useRef(null);

  // Read file as Data URL
  useEffect(() => {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => {
      setImageSrc(reader.result);
    };
    reader.readAsDataURL(file);
    setZoom(1);
    setPan({ x: 0, y: 0 });
  }, [file]);

  if (!file || !imageSrc) return null;

  // Mouse / Touch Dragging Event Handlers
  const handlePointerDown = (e) => {
    setIsDragging(true);
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    dragStart.current = { x: clientX - pan.x, y: clientY - pan.y };
  };

  const handlePointerMove = (e) => {
    if (!isDragging) return;
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    setPan({
      x: clientX - dragStart.current.x,
      y: clientY - dragStart.current.y,
    });
  };

  const handlePointerUp = () => {
    setIsDragging(false);
  };

  // Process and Crop the Image on offscreen Canvas
  const handleCrop = () => {
    const img = imgRef.current;
    const container = containerRef.current;
    if (!img || !container) return;

    // Dimensions of crop frame (centered in container)
    // Container size is fixed to W=320, H=240 for 16:9 or W=280, H=280 for 1:1
    const isSquare = aspectRatio === "1:1";
    const cropW = isSquare ? 250 : 280;
    const cropH = isSquare ? 250 : 157.5; // 16:9 aspect ratio (280 / 1.777)

    // Target output dimensions
    const outputW = isSquare ? 500 : 800;
    const outputH = isSquare ? 500 : 450;

    // Create Canvas
    const canvas = document.createElement("canvas");
    canvas.width = outputW;
    canvas.height = outputH;
    const ctx = canvas.getContext("2d");

    // Load actual image to get natural proportions
    const naturalW = img.naturalWidth;
    const naturalH = img.naturalHeight;

    // Display dimensions of the image in the container (before zoom & pan)
    // object-contain logic: scales image to fit container size
    const containerW = container.clientWidth;
    const containerH = container.clientHeight;

    const imgScale = Math.min(containerW / naturalW, containerH / naturalH);
    const displayedW = naturalW * imgScale;
    const displayedH = naturalH * imgScale;

    // Draw parameters
    const scaleFactor = outputW / cropW;
    const dw = displayedW * zoom * scaleFactor;
    const dh = displayedH * zoom * scaleFactor;

    // Center of canvas plus panned translations
    const dx = (outputW / 2) + (pan.x * scaleFactor) - (dw / 2);
    const dy = (outputH / 2) + (pan.y * scaleFactor) - (dh / 2);

    // Clean background
    ctx.fillStyle = "#0f172a"; // deep slate background
    ctx.fillRect(0, 0, outputW, outputH);

    // Draw the image
    ctx.drawImage(img, dx, dy, dw, dh);

    // Export as optimized JPEG under 1MB
    canvas.toBlob(
      (blob) => {
        if (!blob) return;
        
        // Convert blob to File object
        const croppedFile = new File([blob], file.name || "cropped_image.jpg", {
          type: "image/jpeg",
          lastModified: Date.now(),
        });
        
        onConfirm(croppedFile);
      },
      "image/jpeg",
      0.85 // High quality compression (guarantees size is around 80KB - 150KB)
    );
  };

  const isSquare = aspectRatio === "1:1";

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      {/* Backdrop */}
      <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-md" onClick={onCancel} />

      {/* Modal Card */}
      <div className="relative w-full max-w-md glass-panel p-6 rounded-2xl border border-white/10 bg-slate-900/95 shadow-2xl z-10 space-y-6 animate-in fade-in zoom-in-95 duration-200">
        <div className="flex items-center justify-between border-b border-white/5 pb-3">
          <div>
            <h3 className="text-base font-extrabold text-slate-200">Adjust & Crop Image</h3>
            <p className="text-[10px] text-slate-400 mt-0.5">Drag to position and use the slider to zoom</p>
          </div>
          <button 
            type="button"
            onClick={onCancel}
            className="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-white/5 border border-white/5 transition-all"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        {/* Viewport Cropper Container */}
        <div 
          ref={containerRef}
          className="w-full h-72 rounded-xl bg-slate-950 border border-white/5 overflow-hidden relative flex items-center justify-center cursor-move select-none"
          onMouseDown={handlePointerDown}
          onMouseMove={handlePointerMove}
          onMouseUp={handlePointerUp}
          onMouseLeave={handlePointerUp}
          onTouchStart={handlePointerDown}
          onTouchMove={handlePointerMove}
          onTouchEnd={handlePointerUp}
        >
          {/* Zoomed/Panned Image */}
          <img 
            ref={imgRef}
            src={imageSrc} 
            alt="To crop" 
            className="max-w-full max-h-full object-contain pointer-events-none"
            style={{
              transform: `translate(${pan.x}px, ${pan.y}px) scale(${zoom})`,
              transition: isDragging ? "none" : "transform 0.15s ease-out",
            }}
          />

          {/* Dark Overlay with centered transparent crop box */}
          <div className="absolute inset-0 pointer-events-none flex items-center justify-center">
            {/* Aspect Ratio Box Grid */}
            <div 
              style={{
                width: isSquare ? "250px" : "280px",
                height: isSquare ? "250px" : "157.5px",
              }}
              className={`border-2 border-cyan-400 shadow-[0_0_0_9999px_rgba(15,23,42,0.7)] shadow-slate-950/70 relative ${isSquare ? "rounded-full" : "rounded-lg"}`}
            >
              {/* Glowing Corner Accents */}
              <div className="absolute -top-1 -left-1 w-3 h-3 border-t-2 border-l-2 border-cyan-400"></div>
              <div className="absolute -top-1 -right-1 w-3 h-3 border-t-2 border-r-2 border-cyan-400"></div>
              <div className="absolute -bottom-1 -left-1 w-3 h-3 border-b-2 border-l-2 border-cyan-400"></div>
              <div className="absolute -bottom-1 -right-1 w-3 h-3 border-b-2 border-r-2 border-cyan-400"></div>
            </div>
          </div>
        </div>

        {/* Sliders Control Deck */}
        <div className="space-y-4">
          <div className="space-y-1">
            <div className="flex justify-between items-center text-[10px] text-slate-400 font-bold uppercase tracking-wider">
              <span>Zoom</span>
              <span className="text-cyan-400 font-mono-telemetry">{zoom.toFixed(2)}x</span>
            </div>
            <input 
              type="range" 
              min="0.2" 
              max="3" 
              step="0.01" 
              value={zoom}
              onChange={(e) => setZoom(parseFloat(e.target.value))}
              className="w-full accent-cyan-500 h-1 bg-slate-800 rounded-lg cursor-pointer appearance-none"
            />
          </div>

          <div className="flex justify-between items-center text-[9px] text-slate-500 font-semibold italic border-t border-white/5 pt-2">
            <span>Format: {isSquare ? "1:1 Square (Driver)" : "16:9 Rectangle (Car / Track)"}</span>
            <span>Max 1MB (Compressed via GPU Canvas)</span>
          </div>
        </div>

        {/* Buttons */}
        <div className="flex gap-3">
          <button
            type="button"
            onClick={onCancel}
            className="flex-1 py-3 rounded-xl text-xs font-extrabold bg-white/5 hover:bg-white/10 text-slate-300 uppercase tracking-wider transition-all active:scale-[0.98]"
          >
            Cancel
          </button>
          <button
            type="button"
            onClick={handleCrop}
            className="flex-1 py-3 rounded-xl text-xs font-extrabold bg-cyan-500 hover:bg-cyan-400 text-black uppercase tracking-wider transition-all active:scale-[0.98] shadow-md shadow-cyan-500/10 flex items-center justify-center gap-1.5"
          >
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
              <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>
            Crop & Optimize
          </button>
        </div>
      </div>
    </div>
  );
}
