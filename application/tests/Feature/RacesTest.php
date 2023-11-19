<?php

use App\Models\Championship;
use App\Models\Race;
use App\Models\User;

use function Pest\Laravel\{actingAs};

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->championship = Championship::factory()->create();
    Race::factory()->for($this->championship)->create(['name' => 'Fast and Furious']);
});

it('has races page', function (): void {
    $response = $this->get('/races');

    $response->assertStatus(200);
});

it('has races api', function (): void {
    $response = $this->get('/api/races');

    $response->assertStatus(200)->assertJson([
        'data' => [['id' => 1, 'name'  => 'Fast and Furious']],
    ]);
});

it('get race by id', function (): void {
    $response = $this->get('/api/races/1');

    $response->assertStatus(200)->assertJson([
        'data' => ['id' => 1, 'name'  => 'Fast and Furious'],
    ]);
});

it('create a race', function (): void {
    $response = actingAs($this->user)->postJson('/api/races/create', [
        'name'  => 'Tokio Drift',
        'laps' => 5,
        'date_start' => '2023-03-01',
        'time_start' => '15:00',
        'championship_id' => $this->championship->id,
    ]);

    $response->assertStatus(201);

    $response = $this->get('/api/races/2');

    $response->assertStatus(200)->assertJson([
        'data' => ['id' => 2, 'name'  => 'Tokio Drift'],
    ]);
});

it('update a race', function (): void {
    $response = actingAs($this->user)->putJson('/api/races/update/1', [
        'name'  => 'Crazy Frog',
        'laps' => 5,
        'date_start' => '2023-03-02',
        'time_start' => '16:00',
        'championship_id' => $this->championship->id
    ]);

    $response->assertStatus(200);

    $response = $this->get('/api/races/1');

    $response->assertStatus(200)->assertJson([
        'data' => ['id' => 1, 'name'  => 'Crazy Frog', 'laps' => 5],
    ]);
});


it('not create a race', function (): void {
    $response = actingAs($this->user)->postJson('/api/races/create', ['name'  => fake()->text(4000), 'type' => false]);

    $response->assertStatus(422)->assertExactJson([
        'message'  => 'The name may not be greater than 255 characters. (and 4 more errors)',
        'errors' => [
            'championship_id' => ['The championship id field is required.'],
            'date_start' => ['The date start field is required.'],
            'laps' => ['The laps field is required when type is false.'],
            'name' => ['The name may not be greater than 255 characters.'],
            'time_start' => ['The time start field is required.'],
        ]
    ]);
});
