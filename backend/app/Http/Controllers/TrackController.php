<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** @group Tracks - Endpoints for managing slotcar tracks and their absolute records. */
class TrackController extends Controller
{
    /** GET /api/tracks - List all registered tracks and their absolute lap records. */
    public function index(Request $request)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Track::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $tracks = $query->with(['bestLapDriver'])->get();
        return response()->json($tracks);
    }

    /** POST /api/tracks - Register a new track with lane count, length, and optional image upload. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lanes_count' => 'required|integer|min:1|max:8',
            'length_meters' => 'nullable|numeric|min:0',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $path = $request->file('image')->store('tracks', 'public');
            $validated['image'] = $path;
        }

        $userEmail = $request->header('X-User-Email');
        if (!empty($userEmail)) {
            $validated['user_email'] = $userEmail;
        }

        $track = Track::create($validated);
        return response()->json($track, 201);
    }

    /** GET /api/tracks/{id} - Retrieve details of a specific track with its recent race history. */
    public function show(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Track::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $track = $query->with(['bestLapDriver', 'races' => function($q) {
            $q->orderBy('created_at', 'desc')->limit(5);
        }])->findOrFail($id);

        return response()->json($track);
    }

    /** PUT /api/tracks/{id} - Update track data, records, or image. */
    public function update(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Track::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $track = $query->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'lanes_count' => 'sometimes|required|integer|min:1|max:8',
            'length_meters' => 'nullable|numeric|min:0',
            'best_lap_time' => 'nullable|numeric|min:0',
            'best_lap_driver_id' => 'nullable|exists:drivers,id',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if (!empty($track->image) && !filter_var($track->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($track->image);
            }

            $path = $request->file('image')->store('tracks', 'public');
            $validated['image'] = $path;
        } elseif ($request->exists('image') && empty($request->input('image'))) {
            if (!empty($track->image) && !filter_var($track->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($track->image);
            }
            $validated['image'] = null;
        }

        $track->update($validated);
        return response()->json($track);
    }

    /** DELETE /api/tracks/{id} - Delete a registered track and its image file. */
    public function destroy(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Track::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $track = $query->findOrFail($id);

        if (!empty($track->image) && !filter_var($track->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($track->image);
        }

        $track->delete();
        return response()->json(null, 204);
    }
}
