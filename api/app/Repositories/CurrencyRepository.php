<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CurrencyRepository
{
    public const TABLE = 'currencies';

    public function getAllCurrencies()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    public function getCurrency(string $cur)
    {
        return DB::table(self::TABLE)
            ->where('name', $cur)
            ->first();
    }
}
