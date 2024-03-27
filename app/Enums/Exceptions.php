<?php

namespace App\Enums;

use Illuminate\Http\JsonResponse;
use ReflectionClass;
use Throwable;

enum Exceptions
{
    case AuthenticationException;
    case TooManyRequestsHttpException;
    case InternalServerErrorException;
    case ModelNotFoundException;
    case CustomException;

    /**
     * @param Throwable $exception
     * @return mixed
     */
    public static function getException(Throwable $exception): mixed
    {
        $value = new ReflectionClass($exception);

        if (defined("self::$value")) {
            return constant("self::$value");
        }
        return constant("self::CustomException");
    }

    /**
     * @param Throwable $exception
     * @param null $message
     * @param null $code
     * @return JsonResponse
     */
    public function setException(Throwable $exception, $message = null, $code = null): JsonResponse
    {
        return match ($this) {
            Exceptions::AuthenticationException => response()->json([
                'success' => false,
                'message' => $message ?? $exception->getMessage()
            ], 403),
            Exceptions::TooManyRequestsHttpException => response()->json([
                'success' => false,
                'message' => $message ?? $exception->getMessage()
            ], 429),
            Exceptions::ModelNotFoundException => response()->json([
                'success' => false,
                'message' => $message ?? $exception->getMessage()
            ], 404),
            Exceptions::InternalServerErrorException => response()->json([
                'success' => false,
                'message' => $message ?? $exception->getMessage()
            ], 500),
            Exceptions::CustomException => response()->json([
                'success' => false,
                'message' => $message ?? $exception->getMessage()
            ], $code ?? 400)
        };
    }
}
