<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Notifications\AlertNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Telegram\TelegramUpdates;

class OrderListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @throws \JsonException
     */
    public function handle(OrderEvent $event): void
    {
        $event->order->load(['info', 'info.type']);

        $message = (object)[
            'username' => $event->username,
            'placed' => $event->placedBy,
            'amount' => $event->order->amount,
            'name' => $event->order->info->name,
            'size' => $event->order->info->type->size->size,
            'season' => $event->order->info->type->season->value,
            'income' => $event->order->info->price * $event->order->amount,
            'place' => $event->order->info->place->name
        ];

        Notification::send(['324831903'], new AlertNotification($message));

    }
}
