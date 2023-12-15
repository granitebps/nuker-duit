<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    public const TABLE = 'transactions';

    public function getSummaryTransactions(array $param)
    {
        return DB::table(CurrencyRepository::TABLE)
            ->leftJoin('transactions', function ($join) use ($param) {
                $join->on('currencies.id', '=', 'transactions.currency_id');
                $join->on('transactions.created_at', '>=', DB::raw(sprintf("'%s'", $param['start'])));
                $join->on('transactions.created_at', '<=', DB::raw(sprintf("'%s'", $param['end'])));
            })
            ->select(DB::raw("currencies.name as currency_name, COALESCE(SUM(CASE WHEN transactions.side = 'buy' THEN transactions.total ELSE 0 END), 0) AS total_buy, COALESCE(SUM(CASE WHEN transactions.side = 'sell' THEN transactions.total ELSE 0 END), 0) AS total_sell, COALESCE(SUM(CASE WHEN transactions.side = 'buy' THEN transactions.total ELSE -transactions.total END), 0) AS net_total"))
            ->groupBy('currencies.name')
            ->get();
    }

    public function createTransaction(array $input)
    {
        return DB::table(self::TABLE)
            ->insert([
                'currency_id' => $input['currency_id'],
                'side' => $input['side'],
                'amount' => $input['amount'],
                'rate' => $input['rate'],
                'total' => $input['total'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
