<?php

use App\Models\Championship;

beforeEach(function (): void {
    Championship::factory()->create(['name' => 'World Copa Cup']);
});


it('has ranking page', function (): void {
    $response = $this->get('/ranking');

    $response->assertStatus(200);
});


it('has championships api', function (): void {
    $response = $this->get('/api/championships');

    $response->assertStatus(200)->assertJson([
        'data' => [['id' => 1, 'name'  => 'World Copa Cup']],
    ]);
});

it('get championship by id', function (): void {
    $response = $this->get('/api/championships/1');

    $response->assertStatus(200)->assertJson(['name'  => 'World Copa Cup']);
});

it('create a championship', function (): void {
    $response = $this->postJson('/api/championships/create', ['name'  => 'Banana World']);

    $response->assertStatus(201);

    $response = $this->get('/api/championships/2');

    $response->assertStatus(200)->assertJson(['name'  => 'Banana World']);
});

it('update a championship', function (): void {
    $response = $this->putJson('/api/championships/update/1', ['name'  => 'Long Board']);

    $response->assertStatus(200);

    $response = $this->get('/api/championships/1');

    $response->assertStatus(200)->assertJson(['name'  => 'Long Board']);
});

it('delete a championship', function (): void {
    $response = $this->delete('/api/championships/delete/1');

    $response->assertStatus(200);

    $response = $this->get('/api/championships');

    $response->assertStatus(200)->assertJson([
        'data' => [],
    ]);
});
