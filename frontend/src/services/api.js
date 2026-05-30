// frontend/src/services/api.js
// Google Drive Virtual Database Client (Eliminates the backend SQLite local DB)

let dbCache = {
  drivers: [],
  cars: [],
  tracks: [],
  races: [],
  settings: { lang: "en" }
};

const getUserEmail = () => {
  if (typeof window === 'undefined') return null;
  try {
    const userStr = localStorage.getItem("slotrace_user");
    if (userStr) {
      const user = JSON.parse(userStr);
      return user?.email || null;
    }
  } catch (e) {
    // fail silently
  }
  return null;
};

const loadCache = () => {
  const email = getUserEmail();
  if (!email) {
    dbCache = { drivers: [], cars: [], tracks: [], races: [], settings: { lang: "en" } };
    return;
  }
  try {
    const local = localStorage.getItem(`slotrace_cloud_cache_${email}`);
    if (local) {
      dbCache = JSON.parse(local);
      if (!dbCache.settings) {
        dbCache.settings = { lang: "en" };
      }
    } else {
      dbCache = { drivers: [], cars: [], tracks: [], races: [], settings: { lang: "en" } };
    }
  } catch (e) {
    console.error("Erro ao carregar cache local:", e);
  }
};

const saveCache = () => {
  const email = getUserEmail();
  if (!email) return;
  try {
    localStorage.setItem(`slotrace_cloud_cache_${email}`, JSON.stringify(dbCache));
    // Trigger auto-sync event in the browser
    if (typeof window !== 'undefined') {
      window.dispatchEvent(new Event("slotrace_db_changed"));
    }
  } catch (e) {
    console.error("Error saving local cache:", e);
  }
};

// Convert File objects to Base64 data URL
const fileToBase64 = (file) => {
  if (!file || !(file instanceof File)) return Promise.resolve(null);
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => resolve(reader.result);
    reader.onerror = (e) => reject(e);
    reader.readAsDataURL(file);
  });
};

// Extracts FormData values and handles base64 image conversion
const extractFormData = async (data, fileKeys = []) => {
  if (!(data instanceof FormData)) {
    return { ...data };
  }
  
  const obj = {};
  for (const [key, value] of data.entries()) {
    if (fileKeys.includes(key)) {
      if (value instanceof File && value.size > 0) {
        obj[key] = await fileToBase64(value);
        obj[`${key}_size_formatted`] = `${(value.size / 1024).toFixed(0)} KB`;
      } else if (typeof value === "string") {
        obj[key] = value || null;
        obj[`${key}_size_formatted`] = null;
      }
    } else {
      obj[key] = value;
    }
  }
  return obj;
};

// Virtual database API interfaces matching original Laravel endpoints structure
export const api = {
  drivers: {
    list: () => {
      loadCache();
      const driversWithStats = dbCache.drivers.map(d => {
        const driverRaces = dbCache.races.filter(r => 
          (r.participants || []).some(p => p.driver_id === d.id)
        );
        const racesCount = driverRaces.length;
        
        let totalLaps = 0;
        driverRaces.forEach(r => {
          const laps = (r.lap_times || []).filter(l => l.driver_id === d.id);
          totalLaps += laps.length;
        });

        return {
          ...d,
          races_count: racesCount,
          total_laps: totalLaps
        };
      });
      // Returns list sorted by name
      return Promise.resolve(driversWithStats.sort((a,b) => a.name.localeCompare(b.name)));
    },
    create: async (data) => {
      loadCache();
      const parsed = await extractFormData(data, ['avatar']);
      const newDriver = {
        id: 'dr_' + Date.now(),
        name: parsed.name,
        nickname: parsed.nickname || "",
        avatar_url: parsed.avatar || null,
        avatar_size_formatted: parsed.avatar_size_formatted || null,
        created_at: new Date().toISOString()
      };
      dbCache.drivers.push(newDriver);
      saveCache();
      return Promise.resolve(newDriver);
    },
    show: (id) => {
      loadCache();
      const d = dbCache.drivers.find(x => x.id === id);
      if (!d) return Promise.reject(new Error("Driver not found."));
      
      const driverRaces = dbCache.races.filter(r => 
        (r.participants || []).some(p => p.driver_id === d.id)
      );
      const racesCount = driverRaces.length;
      
      let totalLaps = 0;
      driverRaces.forEach(r => {
        const laps = (r.lap_times || []).filter(l => l.driver_id === d.id);
        totalLaps += laps.length;
      });

      return Promise.resolve({
        ...d,
        races_count: racesCount,
        total_laps: totalLaps
      });
    },
    update: async (id, data) => {
      loadCache();
      const index = dbCache.drivers.findIndex(d => d.id === id);
      if (index === -1) return Promise.reject(new Error("Driver not found."));
      
      const prevDriver = dbCache.drivers[index];
      const parsed = await extractFormData(data, ['avatar']);
      
      const hasAvatarKey = (data instanceof FormData) ? data.has('avatar') : 'avatar' in data;
      let avatarUrl = prevDriver.avatar_url;
      let avatarSize = prevDriver.avatar_size_formatted;
      
      if (hasAvatarKey) {
        if (parsed.avatar === "") {
          avatarUrl = null;
          avatarSize = null;
        } else if (parsed.avatar) {
          avatarUrl = parsed.avatar;
          avatarSize = parsed.avatar_size_formatted;
        }
      }

      dbCache.drivers[index] = {
        ...prevDriver,
        name: parsed.name ?? prevDriver.name,
        nickname: parsed.nickname ?? prevDriver.nickname,
        avatar_url: avatarUrl,
        avatar_size_formatted: avatarSize
      };
      
      saveCache();
      return Promise.resolve(dbCache.drivers[index]);
    },
    delete: (id) => {
      loadCache();
      dbCache.drivers = dbCache.drivers.filter(d => d.id !== id);
      // Clean up car associations
      dbCache.cars = dbCache.cars.map(c => c.driver_id === id ? { ...c, driver_id: null } : c);
      saveCache();
      return Promise.resolve(null);
    }
  },

  tracks: {
    list: () => {
      loadCache();
      return Promise.resolve(
        dbCache.tracks.map(t => {
          const bestDriver = dbCache.drivers.find(d => d.id === t.best_lap_driver_id);
          return {
            ...t,
            best_lap_driver: bestDriver || null
          };
        }).sort((a,b) => a.name.localeCompare(b.name))
      );
    },
    create: async (data) => {
      loadCache();
      const parsed = await extractFormData(data, ['image']);
      const newTrack = {
        id: 'tr_' + Date.now(),
        name: parsed.name,
        lanes_count: parseInt(parsed.lanes_count) || 2,
        length_meters: parseFloat(parsed.length_meters) || null,
        image_url: parsed.image || null,
        image_size_formatted: parsed.image_size_formatted || null,
        best_lap_time: null,
        best_lap_driver_id: null,
        created_at: new Date().toISOString()
      };
      dbCache.tracks.push(newTrack);
      saveCache();
      return Promise.resolve(newTrack);
    },
    show: (id) => {
      loadCache();
      const track = dbCache.tracks.find(t => t.id === id);
      if (!track) return Promise.reject(new Error("Track not found."));
      const bestDriver = dbCache.drivers.find(d => d.id === track.best_lap_driver_id);
      return Promise.resolve({
        ...track,
        best_lap_driver: bestDriver || null
      });
    },
    update: async (id, data) => {
      loadCache();
      const index = dbCache.tracks.findIndex(t => t.id === id);
      if (index === -1) return Promise.reject(new Error("Track not found."));
      
      const prevTrack = dbCache.tracks[index];
      const parsed = await extractFormData(data, ['image']);
      
      const hasImageKey = (data instanceof FormData) ? data.has('image') : 'image' in data;
      let imageUrl = prevTrack.image_url;
      let imageSize = prevTrack.image_size_formatted;
      
      if (hasImageKey) {
        if (parsed.image === "") {
          imageUrl = null;
          imageSize = null;
        } else if (parsed.image) {
          imageUrl = parsed.image;
          imageSize = parsed.image_size_formatted;
        }
      }

      dbCache.tracks[index] = {
        ...prevTrack,
        name: parsed.name ?? prevTrack.name,
        lanes_count: parsed.lanes_count ? parseInt(parsed.lanes_count) : prevTrack.lanes_count,
        length_meters: parsed.length_meters ? parseFloat(parsed.length_meters) : prevTrack.length_meters,
        image_url: imageUrl,
        image_size_formatted: imageSize
      };
      
      saveCache();
      return Promise.resolve(dbCache.tracks[index]);
    },
    delete: (id) => {
      loadCache();
      dbCache.tracks = dbCache.tracks.filter(t => t.id !== id);
      saveCache();
      return Promise.resolve(null);
    }
  },

  cars: {
    list: () => {
      loadCache();
      return Promise.resolve(
        dbCache.cars.map(c => {
          const driver = dbCache.drivers.find(d => d.id === c.driver_id);
          return {
            ...c,
            driver: driver || null
          };
        }).sort((a,b) => a.name.localeCompare(b.name))
      );
    },
    create: async (data) => {
      loadCache();
      const parsed = await extractFormData(data, ['image']);
      const newCar = {
        id: 'cr_' + Date.now(),
        name: parsed.name,
        brand: parsed.brand || "",
        model: parsed.model || "",
        scale: parsed.scale || "1:32",
        driver_id: parsed.driver_id || null,
        image_url: parsed.image || null,
        image_size_formatted: parsed.image_size_formatted || null,
        created_at: new Date().toISOString()
      };
      dbCache.cars.push(newCar);
      saveCache();
      return Promise.resolve(newCar);
    },
    update: async (id, data) => {
      loadCache();
      const index = dbCache.cars.findIndex(c => c.id === id);
      if (index === -1) return Promise.reject(new Error("Car not found."));
      
      const prevCar = dbCache.cars[index];
      const parsed = await extractFormData(data, ['image']);
      
      const hasImageKey = (data instanceof FormData) ? data.has('image') : 'image' in data;
      let imageUrl = prevCar.image_url;
      let imageSize = prevCar.image_size_formatted;
      
      if (hasImageKey) {
        if (parsed.image === "") {
          imageUrl = null;
          imageSize = null;
        } else if (parsed.image) {
          imageUrl = parsed.image;
          imageSize = parsed.image_size_formatted;
        }
      }

      dbCache.cars[index] = {
        ...prevCar,
        name: parsed.name ?? prevCar.name,
        brand: parsed.brand ?? prevCar.brand,
        model: parsed.model ?? prevCar.model,
        scale: parsed.scale ?? prevCar.scale,
        driver_id: parsed.driver_id ?? prevCar.driver_id,
        image_url: imageUrl,
        image_size_formatted: imageSize
      };
      
      saveCache();
      return Promise.resolve(dbCache.cars[index]);
    },
    delete: (id) => {
      loadCache();
      dbCache.cars = dbCache.cars.filter(c => c.id !== id);
      saveCache();
      return Promise.resolve(null);
    }
  },

  races: {
    list: () => {
      loadCache();
      return Promise.resolve(
        dbCache.races.map(r => {
          const track = dbCache.tracks.find(t => t.id === r.track_id);
          return {
            ...r,
            track: track || null,
            participants_count: (r.participants || []).length
          };
        }).sort((a,b) => new Date(b.created_at) - new Date(a.created_at))
      );
    },
    create: (data) => {
      loadCache();
      const newRace = {
        id: 'rc_' + Date.now(),
        name: data.name,
        track_id: data.track_id,
        type: data.type,
        laps_limit: data.laps_limit ? parseInt(data.laps_limit) : null,
        duration_seconds: data.duration_seconds ? parseInt(data.duration_seconds) : null,
        status: 'pending',
        participants: (data.participants || []).map(p => ({
          driver_id: p.driver_id,
          car_id: p.car_id,
          lane_number: parseInt(p.lane_number)
        })),
        lap_times: [],
        created_at: new Date().toISOString()
      };
      dbCache.races.push(newRace);
      saveCache();
      return Promise.resolve(newRace);
    },
    saveCompleted: async (data) => {
      loadCache();
      const { name, track_id, type, laps_limit, duration_seconds, participants, laps } = data;
      
      const raceId = 'rc_' + Date.now();
      let bestLapTime = null;
      let bestLapDriverId = null;
      
      const mappedLaps = (laps || []).map(l => {
        const lapTime = parseFloat(l.lap_time_seconds);
        if (bestLapTime === null || lapTime < bestLapTime) {
          bestLapTime = lapTime;
          bestLapDriverId = l.driver_id;
        }
        return {
          driver_id: l.driver_id,
          lane_number: parseInt(l.lane_number),
          lap_number: parseInt(l.lap_number),
          lap_time_seconds: lapTime
        };
      });
      
      const newRace = {
        id: raceId,
        name,
        track_id,
        type,
        laps_limit: laps_limit ? parseInt(laps_limit) : null,
        duration_seconds: duration_seconds ? parseInt(duration_seconds) : null,
        status: 'finished',
        participants: (participants || []).map(p => ({
          driver_id: p.driver_id,
          car_id: p.car_id,
          lane_number: parseInt(p.lane_number)
        })),
        lap_times: mappedLaps,
        created_at: new Date().toISOString()
      };
      
      dbCache.races.push(newRace);
      
      // Dynamic track record recalculation on client side
      if (bestLapTime !== null) {
        const trackIndex = dbCache.tracks.findIndex(t => t.id === track_id);
        if (trackIndex !== -1) {
          const track = dbCache.tracks[trackIndex];
          if (track.best_lap_time === null || bestLapTime < track.best_lap_time) {
            dbCache.tracks[trackIndex] = {
              ...track,
              best_lap_time: bestLapTime,
              best_lap_driver_id: bestLapDriverId
            };
          }
        }
      }
      
      saveCache();
      return Promise.resolve(newRace);
    },
    show: (id) => {
      loadCache();
      const race = dbCache.races.find(r => r.id === id);
      if (!race) return Promise.reject(new Error("Race not found."));
      
      const track = dbCache.tracks.find(t => t.id === race.track_id);
      const participants = (race.participants || []).map(p => {
        const driver = dbCache.drivers.find(d => d.id === p.driver_id);
        const car = dbCache.cars.find(c => c.id === p.car_id);
        return {
          ...p,
          driver: driver || null,
          car: car || null
        };
      });
      
      const lapTimes = (race.lap_times || []).map(l => {
        const driver = dbCache.drivers.find(d => d.id === l.driver_id);
        return {
          ...l,
          driver: driver || null
        };
      });
      
      return Promise.resolve({
        ...race,
        track: track || null,
        participants,
        lapTimes
      });
    },
    start: (id) => {
      loadCache();
      const index = dbCache.races.findIndex(r => r.id === id);
      if (index === -1) return Promise.reject(new Error("Race not found."));
      dbCache.races[index].status = 'in_progress';
      saveCache();
      return Promise.resolve(dbCache.races[index]);
    },
    pause: (id) => {
      loadCache();
      const index = dbCache.races.findIndex(r => r.id === id);
      if (index === -1) return Promise.reject(new Error("Race not found."));
      dbCache.races[index].status = 'paused';
      saveCache();
      return Promise.resolve(dbCache.races[index]);
    },
    resume: (id) => {
      loadCache();
      const index = dbCache.races.findIndex(r => r.id === id);
      if (index === -1) return Promise.reject(new Error("Race not found."));
      dbCache.races[index].status = 'in_progress';
      saveCache();
      return Promise.resolve(dbCache.races[index]);
    },
    finish: (id) => {
      loadCache();
      const index = dbCache.races.findIndex(r => r.id === id);
      if (index === -1) return Promise.reject(new Error("Race not found."));
      dbCache.races[index].status = 'finished';
      saveCache();
      return Promise.resolve(dbCache.races[index]);
    },
    delete: (id) => {
      loadCache();
      dbCache.races = dbCache.races.filter(r => r.id !== id);
      saveCache();
      return Promise.resolve(null);
    },
    leaderboard: (id) => {
      loadCache();
      const race = dbCache.races.find(r => r.id === id);
      if (!race) return Promise.reject(new Error("Race not found."));
      
      const leaderboard = (race.participants || []).map(p => {
        const driver = dbCache.drivers.find(d => d.id === p.driver_id);
        const car = dbCache.cars.find(c => c.id === p.car_id);
        const laps = (race.lap_times || []).filter(l => l.driver_id === p.driver_id);
        
        const lapsCompleted = laps.length;
        const bestLap = laps.length > 0 ? Math.min(...laps.map(l => l.lap_time_seconds)) : null;
        const totalTime = laps.reduce((sum, l) => sum + l.lap_time_seconds, 0);
        const lastLap = laps.length > 0 ? laps[laps.length - 1].lap_time_seconds : null;
        
        return {
          driver_id: p.driver_id,
          driver_name: driver?.name || "Unknown",
          driver_nickname: driver?.nickname || "",
          driver_avatar_url: driver?.avatar_url || null,
          car_name: car?.name || "Default Car",
          lane_number: p.lane_number,
          status: race.status === 'finished' ? 'finished' : 'racing',
          laps_completed: lapsCompleted,
          best_lap: bestLap,
          total_time: totalTime,
          last_lap: lastLap,
          laps: laps.map(l => ({
            lap_number: l.lap_number,
            lap_time_seconds: l.lap_time_seconds
          }))
        };
      });

      // Sort leaderboard: laps completed desc, total time asc
      leaderboard.sort((a,b) => {
        if (a.laps_completed !== b.laps_completed) {
          return b.laps_completed - a.laps_completed;
        }
        if (a.total_time !== b.total_time) {
          return a.total_time - b.total_time;
        }
        return a.lane_number - b.lane_number;
      });

      leaderboard.forEach((item, index) => {
        item.position = index + 1;
      });

      return Promise.resolve({
        race: {
          id: race.id,
          name: race.name,
          status: race.status,
          type: race.type,
          laps_limit: race.laps_limit,
          track: dbCache.tracks.find(t => t.id === race.track_id)?.name || "Default Track"
        },
        leaderboard
      });
    }
  },
  settings: {
    get: () => {
      loadCache();
      return Promise.resolve(dbCache.settings || { lang: "en" });
    },
    setLang: (lang) => {
      loadCache();
      if (!dbCache.settings) dbCache.settings = { lang: "en" };
      dbCache.settings.lang = lang;
      saveCache();
      return Promise.resolve(dbCache.settings);
    }
  }
};

// Global helper for Cloud Backup import and export consolidation
export const virtualDb = {
  getExportData: () => {
    loadCache();
    return dbCache;
  },
  importData: (data) => {
    dbCache = {
      drivers: data.drivers || [],
      cars: data.cars || [],
      tracks: data.tracks || [],
      races: data.races || [],
      settings: data.settings || { lang: "en" }
    };
    saveCache();
    // Dispatch rehydration success to reload dashboard and listing components
    if (typeof window !== 'undefined') {
      window.dispatchEvent(new Event("slotrace_data_restored"));
    }
  },
  initSession: () => {
    loadCache();
    if (typeof window !== 'undefined') {
      window.dispatchEvent(new Event("slotrace_data_restored"));
    }
  },
  clearSession: () => {
    dbCache = {
      drivers: [],
      cars: [],
      tracks: [],
      races: [],
      settings: { lang: "en" }
    };
    if (typeof window !== 'undefined') {
      window.dispatchEvent(new Event("slotrace_data_restored"));
    }
  }
};
