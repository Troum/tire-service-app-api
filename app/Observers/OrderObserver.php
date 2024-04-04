<?php

namespace App\Observers;
use App\Events\OrderEvent;
use App\Models\Order;
use App\Services\PdfService;
use Illuminate\Support\Facades\Storage;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $username = $order->user->name;

        if ($order->user_id === request()->user()->id) {
            $placedBy = null;
        } else {
            $placedBy = request()->user()->name;
        }

        $order->load(['info', 'info.type']);

        $data = [
            'orderId' => $order->id,
            'employee' => is_null($placedBy) ?
                $username :
                $username . ' и ' . $placedBy,
            'service' => 'шиномонтаж',
            'count' => $order->amount,
            'price' => $order->info->price * $order->amount,
            'name' => $order->info->name,
            'address' => $order->info->place->address
        ];

        $url = PdfService::makeFile(
            'pdf.order',
            $data,
            'public/orders/',
            'order' . $order->id . '.pdf'
        );

        $order->update(['url' => $url]);
        $order->refresh();

        $message = (object)[
            'username' => $username,
            'placed' => $placedBy,
            'amount' => $order->amount,
            'name' => $order->info->name,
            'size' => $order->info->type->size->size,
            'season' => $order->info->type->season->value,
            'income' => $order->info->price * $order->amount,
            'place' => $order->info->place->name,
            'url' => url($order->url)
        ];

        OrderEvent::dispatch($message);

    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        if (!is_null($order->url)) {
            Storage::delete($order->url);
        }
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
