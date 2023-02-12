<?php


it('has home page and return nuxt', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
    expect($response->getContent())->toContain('nuxt');
});
