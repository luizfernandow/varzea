<?php

use App\Models\Championship;
use App\Models\User;

use function Pest\Laravel\{actingAs};

beforeEach(function (): void {
    $this->user = User::factory()->create();
    Championship::factory()->create(['name' => 'World Copa Cup']);
});


it('has ranking page', function (): void {
    $response = $this->get('/ranking');

    $response->assertStatus(200);
});


it('has championships api', function (): void {
    $response = actingAs($this->user)->get('/api/championships');

    $response->assertStatus(200)->assertJson([
        'data' => [['id' => 1, 'name'  => 'World Copa Cup']],
    ]);
});

it('get championship by id', function (): void {
    $response = actingAs($this->user)->get('/api/championships/1');

    $response->assertStatus(200)->assertJson(['name'  => 'World Copa Cup']);
});

it('create a championship', function (): void {
    $response = actingAs($this->user)->postJson('/api/championships/create', ['name'  => 'Banana World']);

    $response->assertStatus(201);

    $response = $this->get('/api/championships/2');

    $response->assertStatus(200)->assertJson(['name'  => 'Banana World']);
});

it('update a championship', function (): void {
    $response = actingAs($this->user)->putJson('/api/championships/update/1', ['name'  => 'Long Board']);

    $response->assertStatus(200);

    $response = $this->get('/api/championships/1');

    $response->assertStatus(200)->assertJson(['name'  => 'Long Board']);
});

it('delete a championship', function (): void {
    $response = actingAs($this->user)->delete('/api/championships/delete/1');

    $response->assertStatus(200);

    $response = $this->get('/api/championships');

    $response->assertStatus(200)->assertJson([
        'data' => [],
    ]);
});
