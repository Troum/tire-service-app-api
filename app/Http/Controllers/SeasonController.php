<?php

namespace App\Http\Controllers;

use App\Services\SeasonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    private SeasonService $service;

    public function __construct(SeasonService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        return $this->service->getSeasons();
    }
}
