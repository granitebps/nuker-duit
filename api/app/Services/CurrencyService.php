<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Repositories\CurrencyRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;

class CurrencyService
{
    private CurrencyRepository $curRepo;

    public function __construct(CurrencyRepository $curRepo)
    {
        $this->curRepo = $curRepo;
    }

    public function getCurrencies(): array
    {
        $currencies = $this->curRepo->getAllCurrencies();
        $res = [];
        foreach ($currencies as $c) {
            $rate = Http::get(sprintf('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/%s/idr.json', Str::lower($c->name)))->throw()->json();
            array_push($res, [
                'id' => $c->id,
                'currency' => Str::lower($c->name),
                'rate' => Helper::formatCurrencyToString($rate['idr'])
            ]);
        }

        return $res;
    }

    public function getCurrency(string $cur): array
    {
        $currency = $this->curRepo->getCurrency($cur);
        if (empty($currency)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Currency not found");
        }
        $rate = Http::get(sprintf('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/%s/idr.json', $currency->name))->throw()->json();

        return [
            'id' => $currency->id,
            'currency' => Str::lower($currency->name),
            'rate' => Helper::formatCurrencyToString($rate['idr'])
        ];
    }
}
