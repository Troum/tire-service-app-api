<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Notifications\AlertNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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

        $updates = TelegramUpdates::create()->get();

        Storage::makeDirectory('orders');

        Pdf::loadView('pdf.order', [
            'orderId' => $event->order->id,
            'employee' => $event->placedBy,
            'service' => 'шиномонтаж',
            'count' => $event->order->amount,
            'price' => $event->order->info->price * $event->order->amount,
            'name' => $event->order->info->name,
            'address' => $event->order->info->place->address
        ])->save(storage_path('/orders/' . 'order_' . $event->order->id . '.pdf'));



        if ($updates['ok']) {
            if (Arr::has($updates['result'], 0)) {
                $chat_id = Arr::get(Arr::last($updates['result']), 'message.chat.id');

                Notification::route('telegram', $chat_id)
                    ->notify(new AlertNotification($message));
            }
        }

    }
}
