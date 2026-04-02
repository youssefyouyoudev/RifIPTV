<?php

use App\Models\Client;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone_country_code' => '+212',
        'phone_number' => '612345678',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('onboarding.show', absolute: false));

    $user = User::where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->role)->toBe('client');
    expect($user->phone_country_code)->toBe('+212');
    expect($user->phone_number)->toBe('612345678');
    expect(Client::where('user_id', $user->id)->exists())->toBeTrue();
});
