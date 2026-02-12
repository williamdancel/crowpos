<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('pos'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the pos', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('pos'));
    $response->assertOk();
});