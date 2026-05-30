<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** @group Drivers - Endpoints for managing drivers. */
class DriverController extends Controller
{
    /** GET /api/drivers - List all registered drivers with aggregated statistics. */
    public function index(Request $request)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Driver::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $drivers = $query->withCount(['raceParticipants as races_count', 'lapTimes as total_laps'])->get();
        return response()->json($drivers);
    }

    /** POST /api/drivers - Register a new driver on the platform. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'avatar' => 'nullable',
        ]);

        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $userEmail = $request->header('X-User-Email');
        if (!empty($userEmail)) {
            $validated['user_email'] = $userEmail;
        }

        $driver = Driver::create($validated);
        return response()->json($driver, 201);
    }

    /** GET /api/drivers/{id} - Retrieve details of a specific driver including their cars and best lap times. */
    public function show(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Driver::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $driver = $query->with(['cars', 'lapTimes' => function($q) {
            $q->orderBy('lap_time_seconds', 'asc')->limit(10);
        }])->withCount(['raceParticipants as races_count'])->findOrFail($id);

        return response()->json($driver);
    }

    /** PUT /api/drivers/{id} - Update an existing driver's registration details. */
    public function update(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Driver::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $driver = $query->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'avatar' => 'nullable',
        ]);

        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if (!empty($driver->avatar) && !filter_var($driver->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($driver->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        } elseif ($request->exists('avatar') && empty($request->input('avatar'))) {
            if (!empty($driver->avatar) && !filter_var($driver->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($driver->avatar);
            }
            $validated['avatar'] = null;
        }

        $driver->update($validated);
        return response()->json($driver);
    }

    /** DELETE /api/drivers/{id} - Delete a driver and all their associated records from the database. */
    public function destroy(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Driver::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $driver = $query->findOrFail($id);

        if (!empty($driver->avatar) && !filter_var($driver->avatar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($driver->avatar);
        }

        $driver->delete();
        return response()->json(null, 204);
    }
}
