<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    public const TABLE = 'transactions';

    public function getSummaryTransactions(array $param)
    {
        return DB::table(self::TABLE)
            ->join('currencies', 'currencies.id', '=', 'transactions.currency_id')
            ->select(DB::raw("currencies.name as currency_name, SUM(CASE WHEN transactions.side = 'buy' THEN transactions.total ELSE 0 END) AS total_buy, SUM(CASE WHEN transactions.side = 'sell' THEN transactions.total ELSE 0 END) AS total_sell, SUM(CASE WHEN transactions.side = 'buy' THEN transactions.total ELSE -transactions.total END) AS net_total"))
            ->groupBy('currencies.name')
            ->whereBetween('transactions.created_at', [$param['start'], $param['end']])
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
