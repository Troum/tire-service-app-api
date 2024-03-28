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
            $items = Order::with(['info', 'info.type'])->where('user_id', $user_id)->get();
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
            $ordered_with_all = 'совместно с ' . User::select(['id', 'name'])->where('id', $request->user()->id)->first();

        } else {
            $user_id = $request->user()->id;
            $ordered_with_all = 'лично';
        }

        $info_id = $request->get('info_id');
        $amount = $request->get('amount');

        $info = Info::find($info_id);

        $info->update([
            'amount' => $info->amount - $amount
        ]);

        $info->refresh();

        Order::create([
            'user_id' => $user_id,
            'info_id' => $info_id,
            'amount' => $amount,
            'ordered_with_all' => $ordered_with_all
        ]);

        broadcast(new UpdateInfoEvent($info->toArray()));

        return $this->success(['message' => 'Заказ был успешно создан']);
    }
}
