<?php

namespace App\Http\Controllers;

use App\Http\Resources\InfoResource;
use App\Models\Info;
use App\Services\InfoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfoController extends Controller
{

    private InfoService $service;

    public function __construct(InfoService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->service->getAll();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return $this->service->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Info $info): mixed
    {
//        return $this->service->getOne($info->load(['type', 'type.size']));
        return new InfoResource($info->load(['type', 'type.size']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Info $info): JsonResponse
    {
        return $this->service->update($request, $info);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Info $info)
    {
        //
    }
}
