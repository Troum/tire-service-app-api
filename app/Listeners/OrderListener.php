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
        $updates = TelegramUpdates::create()->get();

        if ($updates['ok']) {
            if (Arr::has($updates['result'], 0)) {
                $chat_ids = [];

                foreach ($updates['result'] as $chat) {
                    $chat_ids[] = Arr::get($chat, 'message.chat.id');
                }

                collect($chat_ids)->unique()->each(function ($id) use ($event) {
                    Notification::route('telegram', $id)
                        ->notify(new AlertNotification($event->message));
                });
            }
        }

    }
}
