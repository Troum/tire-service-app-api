<?php

namespace App\Services;

use App\Enums\SeasonEnum;
use App\Http\Resources\Collections\TypeCollection;
use App\Http\Resources\TypeResource;
use App\Interfaces\APIInterface;
use App\Models\Info;
use App\Models\Size;
use App\Models\Type;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeService implements APIInterface
{
    use ResponseHandler;

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        if (request()->query->has('size_id')) {

            $size_id = request()->query->get('size_id');
            $items = Type::where('size_id', $size_id)
                ->where('hide', false)
                ->whereHas('info', function ($query) {
                    $query->where('amount', '>', 0);
                })->get();

            return $this->success(new TypeCollection($items));
        }

        $items = Type::with('size')->get();

        return $this->success(new TypeCollection($items));
    }

    /**
     * @param mixed $model
     * @return JsonResponse
     */
    public function getOne(mixed $model): JsonResponse
    {
        return $this->success(new TypeResource($model));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            Type::create([
                'size_id' => $request->get('size_id'),
                'type' => $request->get('type'),
                'season' => $request->get('season')
            ]);

            return $this->success(['message' => 'Тип был успешно добавлен']);

        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    /**
     * @param Request $request
     * @param Type $type
     * @return JsonResponse
     */
    public function update(Request $request, Type $type): JsonResponse
    {
        try {
            $type->update([
                'size_id' => $request->get('size_id'),
                'type' => $request->get('type'),
                'season' => $request->get('season'),
                'hide' => $request->get('hidden'),
            ]);

            return $this->success(['message' => 'Тип был успешно обновлен']);

        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }
}
