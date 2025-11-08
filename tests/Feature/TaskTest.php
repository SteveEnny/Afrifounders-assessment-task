<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

// // UNAUTHENTICATED USERS ////

test('an unauthenticated users cannot retrieve task', function () {
    getJson('/api/v1/tasks')
        ->assertUnauthorized();
});

test('an unauthenticated users cannot create a task', function () {
    $payload = [
        'title' => 'Test Task',
        'description' => 'This is a test task description',
        'status' => 'pending',
    ];

    postJson('/api/v1/tasks', $payload)
        ->assertUnauthorized();
});

// // AUTHENTICATED USERS ////

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('An authenticated user can create a task', function () {
    $payload = [
        'title' => 'Test Task',
        'description' => 'This is a test task description',
        'status' => 'pending',
    ];
    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/tasks', $payload);

    $response->assertStatus(201);
});

test('An authenticated user can update a task', function () {
    $task = Task::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Test Task',
        'description' => 'This is a test task description',
        'status' => 'pending',
    ]);
    $payload = [
        'title' => 'New test task',
        'description' => 'This is a new test task description',
        'status' => 'completed',
    ];
    $response = $this->actingAs($this->user, 'sanctum')
        ->patchJson(route('tasks.update', 1), $payload);

    $response->assertStatus(200);
});

test('An authenticated user can view their task', function () {
    Task::factory()->count(10)->create([
        'user_id' => $this->user->id,
    ]);
    $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/v1/tasks?status=pending');
    $response->assertStatus(200);
});
