<?php

namespace App\Services;

use App\Events\DatamatrixCreatedEvent;
use App\Http\Resources\Collections\DatamatrixCollection;
use App\Http\Resources\DatamatrixResource;
use App\Interfaces\APIInterface;
use App\Models\Datamatrix;
use App\Traits\ResponseHandler;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DatamatrixService implements APIInterface
{
    use ResponseHandler;

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return $this->success(new DatamatrixCollection(Datamatrix::query()->get(['id', 'tireName', 'url'])));
    }

    /**
     * @param mixed $model
     * @return JsonResponse
     */
    public function getOne(mixed $model): JsonResponse
    {
        return $this->success(new DatamatrixResource($model));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $parts = preg_split('/\s*(?:,|\R)\s*/', $request->get('codes'));
            $codes = array_filter(
                array_map('trim', $parts),
                fn($v) => $v !== ''
            );

            if (empty($codes)) {
                return $this->error(new Exception(), 'Нет кодов', 409);
            }

            $datamatrix = Datamatrix::create([
                'tireName' => $request->get('tireName'),
                'codes'    => $codes,
                'url'      => null,
            ]);

            event(new DatamatrixCreatedEvent($datamatrix));

            return $this->success([
                'success' => true,
                'id'      => $datamatrix->id,
                'message' => 'Генерация запущена, вы получите WebSocket-уведомление, когда архив будет готов.',
            ]);

        } catch (Exception $exception) {
            return $this->error($exception);
        }
    }

    /**
     * @param Datamatrix $model
     * @return JsonResponse
     */
    public function deleteOne(Datamatrix $model): JsonResponse
    {
        try {
            $model->delete();
            Datamatrix::query()->get();
            return $this->success(new DatamatrixCollection(Datamatrix::query()->get(['id', 'tireName', 'url'])));
        } catch (Exception $exception) {
            return $this->error($exception);
        }
    }
}
