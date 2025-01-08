<?php

namespace App\Services;

use App\Events\UpdateInfoEvent;
use App\Http\Resources\Collections\OrderCollection;
use App\Interfaces\APIInterface;
use App\Models\Info;
use App\Models\Order;
use App\Models\User;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrderService implements APIInterface
{
    use ResponseHandler;

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        if (request()->query->has('all')) {
            $items = Order::with(['info', 'info.type', 'user'])->get();
        } else {
            $user_id = request()->user()->id;
            $items = Order::with(['info', 'info.type'])->where('user_id', $user_id)->orderByDesc('created_at')->get();
        }

        return $this->success(new OrderCollection($items));
    }

    public function getOne(mixed $model)
    {
        // TODO: Implement getOne() method.
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->has('employee_id')) {
            $user_id = $request->get('employee_id');
            $ordered_with_all = 'продано совместно с ' . User::select(['id', 'name'])
                    ->where('id', $this->getAuthorizedUserId())
                    ->first()?->name;

        } else {
            $user_id = $this->getAuthorizedUserId();
            $ordered_with_all = 'продано лично';
        }

        $info_id = $request->get('info_id');
        $amount = $request->get('amount');
        $codeIds = $request->get('selected');
        /** @var Info|null $info */

        $info = Info::find($info_id);
        /** @var Collection $original */
        $original = $info->original;

        $filteredItems = $original->filter(function ($item) use ($codeIds) {
            return !in_array($item->id, $codeIds);
        })->values()->toArray();

        $info->update([
            'amount' => $info->amount - $amount,
            'codes'  => $filteredItems,
        ]);

        Order::create([
            'user_id' => $user_id,
            'info_id' => $info_id,
            'amount' => $amount,
            'ordered_with_all' => $ordered_with_all
        ]);

        broadcast(new UpdateInfoEvent($info->id));

        return $this->success(['message' => 'Заказ был успешно создан']);
    }

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function deleteOne(Order $order): JsonResponse
    {
        $order->delete();
        return $this->success(['message' => 'Заказ был успешно удалён']);
    }
}
