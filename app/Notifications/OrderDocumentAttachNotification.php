<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;

class OrderDocumentAttachNotification extends Notification
{
    use Queueable;

    protected string $url;
    protected string $name;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $url, string $name)
    {
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['telegram'];
    }

    /**
     * @return TelegramFile
     */
    public function toTelegram(): TelegramFile
    {
        return TelegramFile::create()
            ->content('Документ к заказу')
            ->file($this->url, $this->name);

    }
}
