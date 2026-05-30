<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\RaceParticipant;
use App\Models\LapTime;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** @group Races - Endpoints for creation, state control, telemetry, and leaderboard rankings of slotcar races. */
class RaceController extends Controller
{
    /** GET /api/races - List race history and active registered GPs. */
    public function index(Request $request)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Race::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        
        $races = $query->with(['track'])
            ->withCount('participants')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($races);
    }

    /** POST /api/races - Create a new slotcar race defining track, type, limits, and participants. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'track_id' => 'required|exists:tracks,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:time_trial,lap_race,endurance',
            'laps_limit' => 'nullable|integer|min:1',
            'duration_seconds' => 'nullable|integer|min:1',
            'participants' => 'required|array|min:1',
            'participants.*.driver_id' => 'required|exists:drivers,id',
            'participants.*.car_id' => 'required|exists:cars,id',
            'participants.*.lane_number' => 'required|integer|min:1',
        ]);

        try {
            $race = DB::transaction(function () use ($validated) {
                $race = Race::create([
                    'track_id' => $validated['track_id'],
                    'name' => $validated['name'],
                    'type' => $validated['type'],
                    'laps_limit' => $validated['laps_limit'] ?? null,
                    'duration_seconds' => $validated['duration_seconds'] ?? null,
                    'status' => 'pending',
                ]);

                foreach ($validated['participants'] as $part) {
                    RaceParticipant::create([
                        'race_id' => $race->id,
                        'driver_id' => $part['driver_id'],
                        'car_id' => $part['car_id'],
                        'lane_number' => $part['lane_number'],
                        'status' => 'ready',
                    ]);
                }

                return $race;
            });

            return response()->json($race->load('participants.driver', 'participants.car', 'track'), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar corrida.', 'error' => $e->getMessage()], 500);
        }
    }

    /** GET /api/races/{id} - Retrieve details of a specific race including track, participants, and telemetry log. */
    public function show(string $id)
    {
        $race = Race::with([
            'track', 
            'participants.driver', 
            'participants.car', 
            'lapTimes.driver'
        ])->findOrFail($id);

        return response()->json($race);
    }

    /** POST /api/races/{id}/start - Start the race (changes status to in_progress and enables telemetry). */
    public function start(string $id)
    {
        $race = Race::findOrFail($id);
        
        if ($race->status !== 'pending') {
            return response()->json(['message' => 'Apenas corridas pendentes podem ser iniciadas.'], 400);
        }

        $race->update(['status' => 'in_progress']);
        
        // Update participants status to 'racing'
        RaceParticipant::where('race_id', $race->id)->update(['status' => 'racing']);

        return response()->json($race->load('participants.driver'));
    }

    /** POST /api/races/{id}/lap - Telemetry: Record a new lap time for a driver/lane, updating leaderboard and records. */
    public function recordLap(Request $request, string $id)
    {
        $race = Race::findOrFail($id);

        if ($race->status === 'paused') {
            return response()->json(['message' => 'A corrida está pausada.'], 400);
        }

        if ($race->status !== 'in_progress') {
            return response()->json(['message' => 'Corrida não está em progresso.'], 400);
        }

        $validated = $request->validate([
            'lane_number' => 'nullable|integer',
            'driver_id' => 'nullable|exists:drivers,id',
            'lap_time_seconds' => 'required|numeric|min:0.001',
        ]);

        if (empty($validated['lane_number']) && empty($validated['driver_id'])) {
            return response()->json(['message' => 'É necessário informar lane_number ou driver_id.'], 422);
        }

        // Find matching participant
        $query = RaceParticipant::where('race_id', $race->id);
        if (!empty($validated['lane_number'])) {
            $query->where('lane_number', $validated['lane_number']);
        } else {
            $query->where('driver_id', $validated['driver_id']);
        }
        
        $participant = $query->first();

        if (!$participant) {
            return response()->json(['message' => 'Participante não encontrado nesta corrida.'], 404);
        }

        if ($participant->status === 'finished') {
            return response()->json(['message' => 'Este piloto já concluiu a corrida.'], 400);
        }

        // Calculate current lap number
        $lapsCount = LapTime::where('race_id', $race->id)
            ->where('driver_id', $participant->driver_id)
            ->count();

        $currentLap = $lapsCount + 1;

        // Record lap time
        $lapTime = LapTime::create([
            'race_id' => $race->id,
            'driver_id' => $participant->driver_id,
            'lane_number' => $participant->lane_number,
            'lap_number' => $currentLap,
            'lap_time_seconds' => $validated['lap_time_seconds'],
        ]);

        // Check and update track record
        $track = Track::findOrFail($race->track_id);
        if (empty($track->best_lap_time) || $validated['lap_time_seconds'] < $track->best_lap_time) {
            $track->update([
                'best_lap_time' => $validated['lap_time_seconds'],
                'best_lap_driver_id' => $participant->driver_id,
            ]);
        }

        // If lap race and hit the limit
        if ($race->type === 'lap_race' && $race->laps_limit && $currentLap >= $race->laps_limit) {
            $participant->update(['status' => 'finished']);
            
            // If all finished, end race automatically
            $activeCount = RaceParticipant::where('race_id', $race->id)
                ->where('status', 'racing')
                ->count();
                
            if ($activeCount === 0) {
                $race->update(['status' => 'finished']);
            }
        }

        return response()->json([
            'lap' => $lapTime,
            'participant_status' => $participant->fresh()->status,
            'race_status' => $race->fresh()->status,
            'is_new_track_record' => $validated['lap_time_seconds'] == $track->fresh()->best_lap_time,
        ]);
    }

    /** POST /api/races/{id}/finish - Manually finish an ongoing race. */
    public function finish(string $id)
    {
        $race = Race::findOrFail($id);

        if ($race->status !== 'in_progress') {
            return response()->json(['message' => 'Apenas corridas em progresso podem ser finalizadas.'], 400);
        }

        $race->update(['status' => 'finished']);
        
        // Update participants still racing to 'finished'
        RaceParticipant::where('race_id', $race->id)
            ->where('status', 'racing')
            ->update(['status' => 'finished']);

        return response()->json($race->load('participants.driver'));
    }

    /** POST /api/races/{id}/pause - Pause an ongoing race. */
    public function pause(string $id)
    {
        $race = Race::findOrFail($id);

        if ($race->status !== 'in_progress') {
            return response()->json(['message' => 'Apenas corridas em andamento podem ser pausadas.'], 400);
        }

        $race->update(['status' => 'paused']);

        // Update participants status to 'paused'
        RaceParticipant::where('race_id', $race->id)
            ->where('status', 'racing')
            ->update(['status' => 'paused']);

        return response()->json($race->load('participants.driver'));
    }

    /** POST /api/races/{id}/resume - Resume a paused race. */
    public function resume(string $id)
    {
        $race = Race::findOrFail($id);

        if ($race->status !== 'paused') {
            return response()->json(['message' => 'Apenas corridas pausadas podem ser retomadas.'], 400);
        }

        $race->update(['status' => 'in_progress']);

        // Update participants status back to 'racing'
        RaceParticipant::where('race_id', $race->id)
            ->where('status', 'paused')
            ->update(['status' => 'racing']);

        return response()->json($race->load('participants.driver'));
    }

    /** GET /api/races/{id}/leaderboard - Real-time leaderboard standings sorted by laps completed and lowest accumulated time. */
    public function leaderboard(string $id)
    {
        $race = Race::with(['track', 'participants.driver', 'participants.car'])->findOrFail($id);

        $participants = $race->participants;
        $leaderboard = [];

        foreach ($participants as $part) {
            // Get lap time telemetry statistics
            $laps = LapTime::where('race_id', $race->id)
                ->where('driver_id', $part->driver_id)
                ->orderBy('lap_number', 'asc')
                ->get();

            $lapsCompleted = $laps->count();
            $bestLap = $laps->min('lap_time_seconds');
            $totalTime = $laps->sum('lap_time_seconds');
            
            // Last recorded lap
            $lastLap = $laps->last();

            $leaderboard[] = [
                'driver_id' => $part->driver_id,
                'driver_name' => $part->driver->name,
                'driver_nickname' => $part->driver->nickname,
                'driver_avatar_url' => $part->driver->avatar_url,
                'car_name' => $part->car->name,
                'lane_number' => $part->lane_number,
                'status' => $part->status,
                'laps_completed' => $lapsCompleted,
                'best_lap' => $bestLap ? floatval($bestLap) : null,
                'total_time' => floatval($totalTime),
                'last_lap' => $lastLap ? floatval($lastLap->lap_time_seconds) : null,
                'laps' => $laps->map(fn($l) => [
                    'lap_number' => $l->lap_number,
                    'lap_time_seconds' => floatval($l->lap_time_seconds),
                ])
            ];
        }

        // Sort leaderboard rankings:
        // 1. Most laps completed (desc)
        // 2. Lowest accumulated time (asc)
        // 3. Keep lane order if no laps recorded
        usort($leaderboard, function ($a, $b) {
            if ($a['laps_completed'] !== $b['laps_completed']) {
                return $b['laps_completed'] <=> $a['laps_completed'];
            }
            if ($a['total_time'] !== $b['total_time']) {
                return $a['total_time'] <=> $b['total_time'];
            }
            return $a['lane_number'] <=> $b['lane_number'];
        });

        // Add podium positions
        foreach ($leaderboard as $index => &$item) {
            $item['position'] = $index + 1;
        }

        return response()->json([
            'race' => [
                'id' => $race->id,
                'name' => $race->name,
                'status' => $race->status,
                'type' => $race->type,
                'laps_limit' => $race->laps_limit,
                'track' => $race->track->name,
            ],
            'leaderboard' => $leaderboard
        ]);
    }

    /** PUT /api/races/{id} - Update basic race registration details. */
    public function update(Request $request, string $id)
    {
        $race = Race::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:pending,in_progress,paused,finished',
        ]);

        $race->update($validated);
        return response()->json($race);
    }

    /** DELETE /api/races/{id} - Delete a race and its telemetry history from the database. */
    public function destroy(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Race::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $race = $query->findOrFail($id);
        $race->delete();
        return response()->json(null, 204);
    }

    /** POST /api/races/save-completed - Telemetry: Bulk save a fully completed GP and all its lap times at once. */
    public function saveCompleted(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'track_id' => 'required|exists:tracks,id',
            'type' => 'required|in:time_trial,lap_race,endurance',
            'laps_limit' => 'nullable|integer|min:1',
            'duration_seconds' => 'nullable|integer|min:1',
            'participants' => 'required|array|min:1',
            'participants.*.driver_id' => 'required|exists:drivers,id',
            'participants.*.car_id' => 'required|exists:cars,id',
            'participants.*.lane_number' => 'required|integer|min:1',
            'laps' => 'present|array',
            'laps.*.driver_id' => 'required|exists:drivers,id',
            'laps.*.lane_number' => 'required|integer|min:1',
            'laps.*.lap_number' => 'required|integer|min:1',
            'laps.*.lap_time_seconds' => 'required|numeric|min:0.001',
        ]);

        $userEmail = $request->header('X-User-Email');

        try {
            $race = DB::transaction(function () use ($validated, $userEmail) {
                // 1. Create completed Race
                $race = Race::create([
                    'track_id' => $validated['track_id'],
                    'name' => $validated['name'],
                    'type' => $validated['type'],
                    'laps_limit' => $validated['laps_limit'] ?? null,
                    'duration_seconds' => $validated['duration_seconds'] ?? null,
                    'status' => 'finished',
                    'user_email' => $userEmail,
                ]);

                // 2. Create participants
                foreach ($validated['participants'] as $part) {
                    RaceParticipant::create([
                        'race_id' => $race->id,
                        'driver_id' => $part['driver_id'],
                        'car_id' => $part['car_id'],
                        'lane_number' => $part['lane_number'],
                        'status' => 'finished',
                    ]);
                }

                // 3. Save all lap times and find the absolute best lap time
                $bestLapTime = null;
                $bestLapDriverId = null;

                foreach ($validated['laps'] as $lap) {
                    LapTime::create([
                        'race_id' => $race->id,
                        'driver_id' => $lap['driver_id'],
                        'lane_number' => $lap['lane_number'],
                        'lap_number' => $lap['lap_number'],
                        'lap_time_seconds' => $lap['lap_time_seconds'],
                    ]);

                    if (is_null($bestLapTime) || $lap['lap_time_seconds'] < $bestLapTime) {
                        $bestLapTime = $lap['lap_time_seconds'];
                        $bestLapDriverId = $lap['driver_id'];
                    }
                }

                // 4. Update track record if this race had a lower time
                if (!is_null($bestLapTime)) {
                    $track = Track::findOrFail($validated['track_id']);
                    if (empty($track->best_lap_time) || $bestLapTime < $track->best_lap_time) {
                        $track->update([
                            'best_lap_time' => $bestLapTime,
                            'best_lap_driver_id' => $bestLapDriverId,
                        ]);
                    }
                }

                return $race;
            });

            return response()->json($race->load('participants.driver', 'participants.car', 'track'), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao salvar resultados da corrida.', 'error' => $e->getMessage()], 500);
        }
    }
}
