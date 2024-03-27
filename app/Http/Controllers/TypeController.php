<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Services\TypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    private TypeService $service;

    public function __construct(TypeService $service)
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
    public function show(Type $type): JsonResponse
    {
        return $this->service->getOne($type);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type): JsonResponse
    {
        return $this->service->update($request, $type);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        //
    }
}
