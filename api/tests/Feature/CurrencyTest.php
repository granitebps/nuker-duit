<?php

use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

test('can get currencies', function () {
    Sanctum::actingAs(User::factory()->create());
    Currency::factory()->create();

    Http::fake([
        'cdn.jsdelivr.net/*' => Http::response(['idr' => 15000], Response::HTTP_OK)
    ]);

    getJson('api/currencies')
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->where('data.0.rate', "15000.00")
                ->etc()
        );
});

test('can get currency', function () {
    Sanctum::actingAs(User::factory()->create());
    Currency::factory()->create();

    Http::fake([
        'cdn.jsdelivr.net/*' => Http::response(['idr' => 15000], Response::HTTP_OK)
    ]);

    getJson('api/currencies/usd')
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->where('data.rate', "15000.00")
                ->etc()
        );
});

test('cant get currencies and currency', function () {
    getJson('api/currencies')
        ->assertUnauthorized();
    getJson('api/currencies/usd')
        ->assertUnauthorized();
});
