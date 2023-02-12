<?php

it('has users page', function (): void {
    $response = $this->get('/users');

    $response->assertStatus(200);
});
