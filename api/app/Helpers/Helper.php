<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class Helper
{
    /**
     * @param string $message
     * @param mixed $result
     * @return JsonResponse
     */
    public static function apiResponse(string $message = 'Success', mixed $result = []): JsonResponse
    {
        if ($result instanceof JsonResource) {
            $response = $result->additional([
                'success' => true,
                'message' => $message,
            ]);
            return ($response)->response()->setStatusCode(Response::HTTP_OK);
        } else {
            $response = [
                'success' => true,
                'message' => $message,
                'data' => $result,
            ];
            return response()->json($response, Response::HTTP_OK);
        }
    }

    /**
     * @param string $message
     * @param int $code
     * @param array $data
     * @return JsonResponse
     */
    public static function apiErrorResponse(string $message = 'Internal Server Error', int $code = Response::HTTP_BAD_REQUEST, array $data = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * @param float $cur
     * @return string
     */
    public static function formatCurrencyToString(float $cur): string
    {
        return number_format($cur, 2, '.', '');
    }
}
