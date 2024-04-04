<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Notifications\AlertNotification;
use App\Notifications\OrderDocumentAttachNotification;
use App\Services\PdfService;
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

        $updates = TelegramUpdates::create()->get();

        $data = [
            'orderId' => $event->order->id,
            'employee' => is_null($event->placedBy) ?
                $event->username :
                $event->username . ' и ' . $event->placedBy,
            'service' => 'шиномонтаж',
            'count' => $event->order->amount,
            'price' => $event->order->info->price * $event->order->amount,
            'name' => $event->order->info->name,
            'address' => $event->order->info->place->address
        ];

        $url = PdfService::makeFile(
            'pdf.order',
            $data,
            'public/orders/',
            'order_' . $event->order->id . '.pdf'
        );

        $message = (object)[
            'username' => $event->username,
            'placed' => $event->placedBy,
            'amount' => $event->order->amount,
            'name' => $event->order->info->name,
            'size' => $event->order->info->type->size->size,
            'season' => $event->order->info->type->season->value,
            'income' => $event->order->info->price * $event->order->amount,
            'place' => $event->order->info->place->name,
            'url' => $url
        ];

        if ($updates['ok']) {
            if (Arr::has($updates['result'], 0)) {
                $chat_ids = [];

                foreach ($updates['result'] as $chat) {
                    $chat_ids[] = Arr::get($chat, 'message.chat.id');
                }

                collect($chat_ids)->unique()->each(function ($id) use ($message, $event) {
                    Notification::route('telegram', $id)
                        ->notify(new AlertNotification($message));
                });
            }
        }

    }
}
