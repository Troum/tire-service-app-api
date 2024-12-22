<?php

namespace App\Traits;

use App\Enums\Exceptions;
use Illuminate\Http\JsonResponse;
use Throwable;

trait ResponseHandler
{
    /**
     * @param mixed $response
     * @return JsonResponse
     */
    public function success(mixed $response = null): JsonResponse
    {
        return response()->json($response ?? ['success' => true]);

    }

    /**
     * @param Throwable $exception
     * @param null $message
     * @param null $code
     * @return JsonResponse
     */
    public function error(Throwable $exception, $message = null, $code = null): JsonResponse
    {
        return Exceptions::getException($exception)
            ->setException($exception, $message, $code);
    }

    /**
     * @param mixed $response
     * @return JsonResponse
     */
    public function warning(mixed $response = null): JsonResponse
    {
        return response()->json($response ?? ['success' => false], 409);

    }
}
