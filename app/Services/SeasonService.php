<?php

namespace App\Services;

use App\Enums\SeasonEnum;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;

class SeasonService
{
    use ResponseHandler;

    /**
     * @return JsonResponse
     */
    public function getSeasons(): JsonResponse
    {
        return $this->success(SeasonEnum::cases());
    }
}
