<?php

use App\Models\Championship;
use App\Models\Race;

beforeEach(function (): void {
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
    $response = $this->postJson('/api/races/create', [
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
