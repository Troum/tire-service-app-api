<?php

namespace App\Services;

use App\Http\Resources\Collections\PlaceCollection;
use App\Interfaces\APIInterface;
use App\Models\Place;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceService implements APIInterface
{
    use ResponseHandler;

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        if (\request()->query->has('place_id')) {
            $place_id = \request()->query->get('place_id');

            $items = Place::with('infos')->where('id', $place_id)->get();

            return $this->success(new PlaceCollection($items));
        }

        $items = Place::with(['infos'])->get();

        return $this->success(new PlaceCollection($items));
    }

    public function getOne(mixed $model)
    {
        // TODO: Implement getOne() method.
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            Place::create([
                'name' => $request->get('name'),
                'address' => $request->get('address')
            ]);

            return $this->success(['message' => 'Сервис был успешно добавлен']);

        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }
}
