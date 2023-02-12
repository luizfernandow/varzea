<?php

it('has users page', function () {
    $response = $this->get('/users');

    $response->assertStatus(200);
});
