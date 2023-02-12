<?php


it('has home page', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
});
