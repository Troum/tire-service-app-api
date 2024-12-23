<?php

namespace App\Services;

use App\Http\Resources\Collections\InfoCollection;
use App\Http\Resources\InfoResource;
use App\Interfaces\APIInterface;
use App\Models\Info;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfoService implements APIInterface
{
    use ResponseHandler;
    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {

        if (request()->query->has('type_id')) {

            $type_id = request()->query->get('type_id');

            $items = Info::with('type')->where('type_id', $type_id)->get();

            return $this->success(new InfoCollection($items));
        }

        $items = Info::with(['type'])->get();

        return $this->success(new InfoCollection($items));
    }

    /**
     * @param mixed $model
     * @return JsonResponse
     */
    public function getOne(mixed $model): JsonResponse
    {
        return $model;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            Info::create([
                'type_id' => $request->get('type_id'),
                'place_id' => $request->get('place_id'),
                'image_url' => $request->get('image_url'),
                'qr_code_hash' => $request->get('qr_code_hash'),
                'name' => $request->get('name'),
                'amount' => $request->get('amount'),
                'price' => $request->get('price')
            ]);

            return $this->success(['message' => 'Информация была успешно добавлена']);

        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    public function update(Request $request, Info $info): JsonResponse
    {
        try {
            $info->update([
                'amount' => $request->get('amount'),
                'price' => $request->get('price')
            ]);

            return $this->success(['message' => 'Информация была успешно обновлена']);

        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }
}
