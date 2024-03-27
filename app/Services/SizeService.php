<?php

namespace App\Services;

use App\Http\Resources\Collections\SizeCollection;
use App\Http\Resources\SizeResource;
use App\Interfaces\APIInterface;
use App\Models\Size;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SizeService implements APIInterface
{
    use ResponseHandler;
    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $items = Size::all();
        return response()->json(new SizeCollection($items));
    }

    /**
     * @param mixed $model
     * @return JsonResponse
     */
    public function getOne(mixed $model): JsonResponse
    {
        return $this->success(new SizeResource($model));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            Size::create([
               'size' => $request->get('size')
            ]);

            return $this->success(['message' => 'Размер был успешно добавлен']);

        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }
}
