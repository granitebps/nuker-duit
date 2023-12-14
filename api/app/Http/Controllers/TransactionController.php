<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\StoreTransactionRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        return Helper::apiResponse('Store transaction success', $this->transactionService->createTransaction($request->validated()));
    }

    public function getSummaryTransactions(Request $request)
    {
        return Helper::apiResponse('Get summary success', $this->transactionService->getSummaryTransactions($request->all()));
    }
}
