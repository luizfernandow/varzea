<?php

use App\Models\Racer;

beforeEach(function (): void {
    Racer::factory()->create(['name' => 'Brian']);
});

it('has racers page', function (): void {
    $response = $this->get('/racers');

    $response->assertStatus(200);
});


it('has racers api', function (): void {
    $response = $this->get('/api/racers');

    $response->assertStatus(200)->assertExactJson([
        'data' => [['id' => 1, 'name'  => 'Brian']],
    ]);
});

it('get racer by id', function (): void {
    $response = $this->get('/api/racers/1');

    $response->assertStatus(200)->assertJson(['name'  => 'Brian']);
});

it('create a racer', function (): void {
    $response = $this->postJson('/api/racers/create', ['name'  => 'Joana']);

    $response->assertStatus(201);

    $response = $this->get('/api/racers/2');

    $response->assertStatus(200)->assertJson(['name'  => 'Joana']);
});

it('update a racer', function (): void {
    $response = $this->putJson('/api/racers/update/1', ['name'  => 'Diesel']);

    $response->assertStatus(200);

    $response = $this->get('/api/racers/1');

    $response->assertStatus(200)->assertJson(['name'  => 'Diesel']);
});

it('delete a racer', function (): void {
    $response = $this->delete('/api/racers/delete/1');

    $response->assertStatus(200);

    $response = $this->get('/api/racers/1');

    $response->assertStatus(404);
});


it('not create a racer', function (): void {
    $response = $this->postJson('/api/racers/create', ['name'  => fake()->text(4000)]);

    $response->assertStatus(422)->assertJson([
        'message'  => 'The name may not be greater than 255 characters.',
        'errors' => ['name' => ['The name may not be greater than 255 characters.']]
    ]);
});
