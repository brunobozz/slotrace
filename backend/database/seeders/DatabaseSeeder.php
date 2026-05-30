<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Track;
use App\Models\Car;
use App\Models\Race;
use App\Models\RaceParticipant;
use App\Models\LapTime;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar Pilotos (Drivers)
        $pilotosData = [
            ['name' => 'Ayrton Senna', 'nickname' => 'Senna'],
            ['name' => 'Alain Prost', 'nickname' => 'Prost'],
            ['name' => 'Michael Schumacher', 'nickname' => 'Schumi'],
            ['name' => 'Lewis Hamilton', 'nickname' => 'Hamilton'],
        ];

        $drivers = [];
        foreach ($pilotosData as $data) {
            $drivers[] = Driver::create($data);
        }

        // 2. Criar Pistas (Tracks)
        $interlagos = Track::create([
            'name' => 'Interlagos (Fenda 2)',
            'lanes_count' => 2,
            'length_meters' => 12.50,
        ]);

        $monza = Track::create([
            'name' => 'Monza Speed (Fenda 4)',
            'lanes_count' => 4,
            'length_meters' => 22.40,
        ]);

        // 3. Criar Carros (Cars)
        $carsData = [
            ['name' => 'McLaren MP4/4', 'brand' => 'Slot.it', 'model' => 'MP4/4', 'scale' => '1:32', 'driver_id' => $drivers[0]->id],
            ['name' => 'Ferrari F2004', 'brand' => 'Carrera', 'model' => 'F2004', 'scale' => '1:32', 'driver_id' => $drivers[2]->id],
            ['name' => 'Williams FW14B', 'brand' => 'NSR', 'model' => 'FW14B', 'scale' => '1:32', 'driver_id' => $drivers[1]->id],
            ['name' => 'Mercedes W11', 'brand' => 'Scalextric', 'model' => 'W11', 'scale' => '1:32', 'driver_id' => $drivers[3]->id],
        ];

        $cars = [];
        foreach ($carsData as $data) {
            $cars[] = Car::create($data);
        }

        // 4. Criar uma Corrida Antiga (Finalizada) com Histórico de Voltas
        $pastRace = Race::create([
            'track_id' => $interlagos->id,
            'name' => 'Desafio Clássico - Senna vs Prost',
            'status' => 'finished',
            'type' => 'lap_race',
            'laps_limit' => 5,
        ]);

        // Participantes da corrida antiga
        $p1 = RaceParticipant::create([
            'race_id' => $pastRace->id,
            'driver_id' => $drivers[0]->id,
            'car_id' => $cars[0]->id,
            'lane_number' => 1,
            'status' => 'finished',
        ]);

        $p2 = RaceParticipant::create([
            'race_id' => $pastRace->id,
            'driver_id' => $drivers[1]->id,
            'car_id' => $cars[2]->id,
            'lane_number' => 2,
            'status' => 'finished',
        ]);

        // Voltas da corrida antiga (Senna ganha)
        $lapsSenna = [5.421, 5.210, 5.180, 5.120, 5.195];
        $lapsProst = [5.610, 5.340, 5.290, 5.211, 5.245];

        for ($i = 0; $i < 5; $i++) {
            LapTime::create([
                'race_id' => $pastRace->id,
                'driver_id' => $drivers[0]->id,
                'lane_number' => 1,
                'lap_number' => $i + 1,
                'lap_time_seconds' => $lapsSenna[$i],
            ]);

            LapTime::create([
                'race_id' => $pastRace->id,
                'driver_id' => $drivers[1]->id,
                'lane_number' => 2,
                'lap_number' => $i + 1,
                'lap_time_seconds' => $lapsProst[$i],
            ]);
        }

        // Atualizar recorde da pista
        $interlagos->update([
            'best_lap_time' => 5.120,
            'best_lap_driver_id' => $drivers[0]->id,
        ]);

        // 5. Criar uma Corrida Pendente (Futura)
        $pendingRace = Race::create([
            'track_id' => $monza->id,
            'name' => 'GP de Monza - Treino Livre',
            'status' => 'pending',
            'type' => 'time_trial',
        ]);
    }
}
