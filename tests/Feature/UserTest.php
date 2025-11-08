<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('a user can register successfully with valid data', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
    ];

    $this->postJson('/api/v1/register', $userData)
        ->assertStatus(201);

    $this->assertDatabaseHas('users', [
        'email' => 'john.doe@example.com',
    ]);
});

test('an existing user can login successfully', function () {
    User::factory()->create([
        'email' => 'testuser@example.com',
        'password' => Hash::make('password'),
    ]);

    $credentials = [
        'email' => 'testuser@example.com',
        'password' => 'password',
    ];

    $this->postJson('/api/v1/login', $credentials)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'token',
                'type',
            ],
        ]);
});

test('user cannot register with an existing email address', function () {
    User::factory()->create([
        'name' => 'John Doe',
        'email' => 'joe.doe@example.com',
        'password' => 'password',
    ]);

    $userData = [
        'name' => 'Jane Smith',
        'email' => 'joe.doe@example.com',
        'password' => 'password',
    ];

    $this->postJson('/api/v1/register', $userData)
        ->assertStatus(422)
        ->assertJsonValidationErrors('email');
});
