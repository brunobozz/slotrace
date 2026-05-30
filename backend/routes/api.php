<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RaceController;
use Illuminate\Support\Facades\Route;

// Rotas de Pilotos
Route::apiResource('drivers', DriverController::class);

// Rotas de Pistas
Route::apiResource('tracks', TrackController::class);

// Rotas de Carros
Route::apiResource('cars', CarController::class);

// Rotas de Corridas
Route::post('races/save-completed', [RaceController::class, 'saveCompleted']);
Route::apiResource('races', RaceController::class);

// Endpoints especiais para controle de corrida e telemetria
Route::post('races/{id}/start', [RaceController::class, 'start']);
Route::post('races/{id}/lap', [RaceController::class, 'recordLap']);
Route::post('races/{id}/finish', [RaceController::class, 'finish']);
Route::post('races/{id}/pause', [RaceController::class, 'pause']);
Route::post('races/{id}/resume', [RaceController::class, 'resume']);
Route::get('races/{id}/leaderboard', [RaceController::class, 'leaderboard']);
