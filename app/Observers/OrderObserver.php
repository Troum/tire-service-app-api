<?php

namespace App\Observers;
use App\Events\OrderEvent;
use App\Models\Order;

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

        OrderEvent::dispatch($order, $username, $placedBy);

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
        //
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
