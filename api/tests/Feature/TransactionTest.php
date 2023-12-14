<?php

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

test('cant store transaction', function () {
    $currency = Currency::factory()->create();

    postJson('api/transactions', [
        'currency_id' => $currency->id,
        'side' => 'buy',
        'amount' => 1,
        'rate' => 15000,
        'total' => 15000
    ])->assertUnauthorized();
});

test('can store transaction', function () {
    Sanctum::actingAs(User::factory()->create());
    $currency = Currency::factory()->create();

    postJson('api/transactions', [
        'currency_id' => $currency->id,
        'side' => 'buy',
        'amount' => 1,
        'rate' => 15000,
        'total' => 15000
    ])->assertOk();

    $this->assertDatabaseHas('transactions', [
        'currency_id' => $currency->id,
        'side' => 'buy',
        'amount' => 1,
        'rate' => 15000,
        'total' => 15000
    ]);
});

test('can get summary', function () {
    Sanctum::actingAs(User::factory()->create());
    $currency = Currency::factory()->create();
    $transaction = Transaction::factory()->for($currency)->create();

    getJson('api/transactions/summary')
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->where('data.0.currency_name', 'usd')
                ->where('data.0.total_buy', $transaction->side == 'buy' ? $transaction->total : 0)
                ->where('data.0.total_sell', $transaction->side == 'sell' ? $transaction->total : 0)
                ->etc()
        );
});
