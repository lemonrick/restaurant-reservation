<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{postJson, getJson};

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

it('admin can create an admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $token = $admin->createToken('api-token')->plainTextToken;

    $response = postJson('/api/register', [
        'first_name' => 'John',
        'last_name' => 'Adminovic',
        'email' => 'admin2@example.com',
        'phone' => '+421912345680',
        'password' => 'secret123',
        'role' => 'admin',
    ], [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertStatus(201);

    $newUser = User::where('email', 'admin2@example.com')->first();
    expect($newUser)->not->toBeNull()
        ->and($newUser->role)->toBe('admin');
});

it('guest cannot create an admin', function () {
    $response = postJson('/api/register', [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane@example.com',
        'phone' => '+421912345679',
        'password' => 'secret123',
        'role' => 'admin', // trying to fake it
    ]);

    $response->assertStatus(201); // still accepted, but forced to guest
    expect(User::where('phone', '+421912345679')->first()->role)->toBe('guest');
});

it('admin can list all users', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $token = $admin->createToken('api-token')->plainTextToken;

    $response = getJson('/api/users', [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertOk();
});

it('guest cannot list users', function () {
    $guest = User::factory()->create(['role' => 'guest']);
    $token = $guest->createToken('api-token')->plainTextToken;

    $response = getJson('/api/users', [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertStatus(403);
});
