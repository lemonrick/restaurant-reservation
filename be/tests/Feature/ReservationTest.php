<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

it('guest can create a reservation', function () {
    $guest = User::factory()->create(['role' => 'guest']);
    $token = $guest->createToken('api-token')->plainTextToken;

    $response = postJson('/api/reservations', [
        'starts_at' => now()->addDay()->setHour(17)->toDateTimeString(),
        'guests_count' => 2,
        'note' => 'Window seat'
    ], [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertOk()
        ->assertJson(['message' => 'The reservation was successfully created.']);
});

it('guest cannot create two reservations in one day', function () {
    $guest = User::factory()->create(['role' => 'guest']);
    $token = $guest->createToken('api-token')->plainTextToken;

    $payload = [
        'starts_at' => now()->addDay()->setHour(17)->toDateTimeString(),
        'guests_count' => 2
    ];

    postJson('/api/reservations', $payload, [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response = postJson('/api/reservations', $payload, [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertStatus(409);
});
