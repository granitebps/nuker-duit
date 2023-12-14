<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;

test('user can login', function () {
    $user = User::factory()->create();

    postJson('api/login', [
        'username' => $user->username,
        'password' => 'password',
    ])->assertOk()
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('data.token')
                ->etc()
        );
});

test('user cant login', function () {
    $user = User::factory()->create();

    postJson('api/login', [
        'username' => $user->username,
        'password' => '12345678',
    ])->assertUnauthorized();
});

test('user can logout', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    deleteJson('api/logout')
        ->assertOk();
});

test('user cant logout', function () {
    deleteJson('api/logout')
        ->assertUnauthorized();
});
