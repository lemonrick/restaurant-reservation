<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

it('logs in with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $response = postJson('/api/login', [
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['user' => ['id', 'token']]);
});

it('fails login with invalid credentials', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $response = postJson('/api/login', [
        'email' => 'john@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422);
});

it('logs out successfully', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = postJson('/api/logout', [], [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertOk();
});
