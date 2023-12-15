<?php

namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepo;

    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }

    public function getSummaryTransactions(array $param)
    {
        $start = '';
        $end = '';
        switch (data_get($param, 'range')) {
            case 'today':
                $start = now()->setHour(0)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s');
                $end = now()->format('Y-m-d H:i:s');
                break;
            case 'week':
                $start = now()->startOfWeek()->setHour(0)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s');
                $end = now()->format('Y-m-d H:i:s');
                break;
            case 'month':
                $start = now()->startOfMonth()->setHour(0)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s');
                $end = now()->format('Y-m-d H:i:s');
                break;
            default:
                $start = now()->setHour(0)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s');
                $end = now()->format('Y-m-d H:i:s');
        }
        $param['start'] = $start;
        $param['end'] = $end;
        return $this->transactionRepo->getSummaryTransactions($param);
    }

    public function createTransaction(array $input)
    {
        return $this->transactionRepo->createTransaction($input);
    }
}
