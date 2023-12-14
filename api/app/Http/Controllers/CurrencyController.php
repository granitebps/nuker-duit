<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    private CurrencyService $curService;

    public function __construct(CurrencyService $curService)
    {
        $this->curService = $curService;
    }

    public function listCurrencies(): JsonResponse
    {
        return Helper::apiResponse('Get currencies success', $this->curService->getCurrencies());
    }

    public function getCurrency(string $currency): JsonResponse
    {
        return Helper::apiResponse('Get currency success', $this->curService->getCurrency($currency));
    }
}
