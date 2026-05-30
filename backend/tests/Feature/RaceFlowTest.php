<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Driver;
use App\Models\Race;
use App\Models\Track;
use App\Models\RaceParticipant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class RaceFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_race_lifecycle_and_telemetry(): void
    {
        // 1. Criar dados base
        $driver1 = Driver::create(['name' => 'Ayrton Senna', 'nickname' => 'Senna']);
        $driver2 = Driver::create(['name' => 'Alain Prost', 'nickname' => 'Prost']);

        $car1 = Car::create([
            'name' => 'McLaren MP4/4',
            'brand' => 'Slot.it',
            'model' => 'MP4/4',
            'driver_id' => $driver1->id
        ]);

        $car2 = Car::create([
            'name' => 'Williams FW14B',
            'brand' => 'NSR',
            'model' => 'FW14B',
            'driver_id' => $driver2->id
        ]);

        $track = Track::create([
            'name' => 'Interlagos',
            'lanes_count' => 2,
            'length_meters' => 12.50
        ]);

        // 2. Criar uma nova corrida (PENDING)
        $raceData = [
            'track_id' => $track->id,
            'name' => 'GP do Brasil',
            'type' => 'lap_race',
            'laps_limit' => 3,
            'participants' => [
                [
                    'driver_id' => $driver1->id,
                    'car_id' => $car1->id,
                    'lane_number' => 1
                ],
                [
                    'driver_id' => $driver2->id,
                    'car_id' => $car2->id,
                    'lane_number' => 2
                ]
            ]
        ];

        $response = $this->postJson('/api/races', $raceData);
        $response->assertStatus(201)
            ->assertJsonPath('name', 'GP do Brasil')
            ->assertJsonPath('status', 'pending');

        $raceId = $response->json('id');

        // 3. Iniciar a corrida
        $response = $this->postJson("/api/races/{$raceId}/start");
        $response->assertStatus(200)
            ->assertJsonPath('status', 'in_progress');

        // 4. Registrar voltas (Telemetria)
        // Volta 1 - Senna fenda 1
        $response = $this->postJson("/api/races/{$raceId}/lap", [
            'lane_number' => 1,
            'lap_time_seconds' => 5.432
        ]);
        $response->assertStatus(200)
            ->assertJsonPath('lap.lap_number', 1)
            ->assertJsonPath('is_new_track_record', true);

        // Volta 1 - Prost fenda 2
        $response = $this->postJson("/api/races/{$raceId}/lap", [
            'lane_number' => 2,
            'lap_time_seconds' => 5.610
        ]);
        $response->assertStatus(200)
            ->assertJsonPath('lap.lap_number', 1)
            ->assertJsonPath('is_new_track_record', false); // Senna fez tempo menor antes

        // Volta 2 - Senna fenda 1 (melhorando o tempo)
        $response = $this->postJson("/api/races/{$raceId}/lap", [
            'lane_number' => 1,
            'lap_time_seconds' => 5.120
        ]);
        $response->assertStatus(200)
            ->assertJsonPath('lap.lap_number', 2)
            ->assertJsonPath('is_new_track_record', true); // Novo recorde da pista!

        // Volta 2 - Prost fenda 2
        $response = $this->postJson("/api/races/{$raceId}/lap", [
            'lane_number' => 2,
            'lap_time_seconds' => 5.300
        ]);

        // Volta 3 - Senna fenda 1 (atingindo limite de 3 voltas)
        $response = $this->postJson("/api/races/{$raceId}/lap", [
            'lane_number' => 1,
            'lap_time_seconds' => 5.150
        ]);
        $response->assertStatus(200)
            ->assertJsonPath('lap.lap_number', 3)
            ->assertJsonPath('participant_status', 'finished'); // Terminou as fendas

        // Volta 3 - Prost fenda 2
        $response = $this->postJson("/api/races/{$raceId}/lap", [
            'lane_number' => 2,
            'lap_time_seconds' => 5.400
        ]);
        $response->assertStatus(200)
            ->assertJsonPath('participant_status', 'finished')
            ->assertJsonPath('race_status', 'finished'); // Ambos terminaram, corrida acabou automaticamente!

        // 5. Validar a classificação (Leaderboard)
        $response = $this->getJson("/api/races/{$raceId}/leaderboard");
        $response->assertStatus(200);

        // Classificação esperada:
        // 1º lugar: Senna (3 voltas, menor tempo acumulado)
        // 2º lugar: Prost (3 voltas, maior tempo acumulado)
        $leaderboard = $response->json('leaderboard');
        
        $this->assertEquals(1, $leaderboard[0]['position']);
        $this->assertEquals('Ayrton Senna', $leaderboard[0]['driver_name']);
        $this->assertEquals(3, $leaderboard[0]['laps_completed']);
        $this->assertEquals(5.120, $leaderboard[0]['best_lap']);

        $this->assertEquals(2, $leaderboard[1]['position']);
        $this->assertEquals('Alain Prost', $leaderboard[1]['driver_name']);
        $this->assertEquals(3, $leaderboard[1]['laps_completed']);
        $this->assertEquals(5.300, $leaderboard[1]['best_lap']);

        // Verificar se o recorde da pista foi persistido na tabela tracks
        $trackFresh = Track::find($track->id);
        $this->assertEquals(5.120, $trackFresh->best_lap_time);
        $this->assertEquals($driver1->id, $trackFresh->best_lap_driver_id);
    }

    public function test_race_pause_and_resume(): void
    {
        $driver = Driver::create(['name' => 'Ayrton Senna', 'nickname' => 'Senna']);
        $car = Car::create([
            'name' => 'McLaren MP4/4',
            'brand' => 'Slot.it',
            'model' => 'MP4/4',
            'driver_id' => $driver->id
        ]);
        $track = Track::create([
            'name' => 'Interlagos',
            'lanes_count' => 2,
            'length_meters' => 12.50
        ]);

        $race = Race::create([
            'track_id' => $track->id,
            'name' => 'GP Test Pause/Resume',
            'type' => 'lap_race',
            'laps_limit' => 5,
            'status' => 'pending',
        ]);

        $participant = RaceParticipant::create([
            'race_id' => $race->id,
            'driver_id' => $driver->id,
            'car_id' => $car->id,
            'lane_number' => 1,
            'status' => 'ready',
        ]);

        // Iniciar
        $this->postJson("/api/races/{$race->id}/start")->assertStatus(200);
        $this->assertEquals('in_progress', $race->fresh()->status);
        $this->assertEquals('racing', $participant->fresh()->status);

        // Pausar
        $response = $this->postJson("/api/races/{$race->id}/pause");
        $response->assertStatus(200);
        $this->assertEquals('paused', $race->fresh()->status);
        $this->assertEquals('paused', $participant->fresh()->status);

        // Tentar registrar volta enquanto pausada (deve falhar)
        $this->postJson("/api/races/{$race->id}/lap", [
            'lane_number' => 1,
            'lap_time_seconds' => 5.432
        ])->assertStatus(400);

        // Retomar
        $response = $this->postJson("/api/races/{$race->id}/resume");
        $response->assertStatus(200);
        $this->assertEquals('in_progress', $race->fresh()->status);
        $this->assertEquals('racing', $participant->fresh()->status);

        // Registrar volta depois de retomar (deve funcionar)
        $this->postJson("/api/races/{$race->id}/lap", [
            'lane_number' => 1,
            'lap_time_seconds' => 5.432
        ])->assertStatus(200);
    }
}

