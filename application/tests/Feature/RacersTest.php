<?php

it('has racers page', function () {
    $response = $this->get('/racers');

    $response->assertStatus(200);
});

