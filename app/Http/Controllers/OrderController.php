<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->service->getAll();
    }

    public function show(Order $order)
    {

    }

    public function update(Request $request, Order $order)
    {

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->service->store($request);
    }

    public function destroy(Order $order): JsonResponse
    {
        return $this->service->deleteOne($order);
    }
}
