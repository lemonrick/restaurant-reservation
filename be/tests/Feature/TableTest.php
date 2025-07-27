<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

it('returns seat options', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $token = $user->createToken('api-token')->plainTextToken;

    $response = getJson('/api/tables/seats', [
        'Authorization' => 'Bearer ' . $token
    ]);

    $response->assertOk()
        ->assertJsonIsArray();
});
