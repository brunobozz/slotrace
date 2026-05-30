<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** @group Cars - Endpoints for managing slotcars in the collection. */
class CarController extends Controller
{
    /** GET /api/cars - List all registered cars with their respective owners. */
    public function index(Request $request)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Car::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $cars = $query->with(['driver'])->get();
        return response()->json($cars);
    }

    /** POST /api/cars - Register a new car in the collection, optionally uploading an image. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'scale' => 'nullable|string|max:50',
            'driver_id' => 'nullable|exists:drivers,id',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $path = $request->file('image')->store('cars', 'public');
            $validated['image'] = $path;
        }

        $userEmail = $request->header('X-User-Email');
        if (!empty($userEmail)) {
            $validated['user_email'] = $userEmail;
        }

        $car = Car::create($validated);
        return response()->json($car, 201);
    }

    /** GET /api/cars/{id} - Retrieve details of a specific car and its owner. */
    public function show(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Car::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $car = $query->with(['driver'])->findOrFail($id);
        return response()->json($car);
    }

    /** PUT /api/cars/{id} - Update a car's details, brand, model, scale, owner, or image. */
    public function update(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Car::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $car = $query->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'scale' => 'nullable|string|max:50',
            'driver_id' => 'nullable|exists:drivers,id',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if (!empty($car->image) && !filter_var($car->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($car->image);
            }

            $path = $request->file('image')->store('cars', 'public');
            $validated['image'] = $path;
        } elseif ($request->exists('image') && empty($request->input('image'))) {
            if (!empty($car->image) && !filter_var($car->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($car->image);
            }
            $validated['image'] = null;
        }

        $car->update($validated);
        return response()->json($car);
    }

    /** DELETE /api/cars/{id} - Delete a registered car from the collection and its image file. */
    public function destroy(Request $request, string $id)
    {
        $userEmail = $request->header('X-User-Email');
        $query = Car::query();
        if (!empty($userEmail)) {
            $query->where('user_email', $userEmail);
        }
        $car = $query->findOrFail($id);

        if (!empty($car->image) && !filter_var($car->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();
        return response()->json(null, 204);
    }
}
