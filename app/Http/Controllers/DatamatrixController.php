<?php

namespace App\Http\Controllers;

use App\Models\Datamatrix;
use App\Services\DatamatrixService;
use Illuminate\Http\Request;

class DatamatrixController extends Controller
{
    public function __construct(private readonly DatamatrixService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->service->getAll();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Datamatrix $datamatrix)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Datamatrix $datamatrix)
    {
        return $this->service->deleteOne($datamatrix);
    }
}
