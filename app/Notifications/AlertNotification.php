<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class AlertNotification extends Notification
{
    use Queueable;

    const SEASONS = [
        'w' => 'Зима',
        's' => 'Лето',
        'a' => 'Всесезонная'
    ];

    protected object $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(object $message)
    {
        $this->message = $message;
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
     * @throws \JsonException
     */
    public function toTelegram(): TelegramMessage
    {
        if (is_null($this->message->placed)) {
            return TelegramMessage::create()
                ->line('*Сервис*: ' . $this->message->place)
                ->line('*Продано сотрудником*: ' . $this->message->username)
                ->line('*Продано*: ' . $this->message->amount . ' ед.')
                ->line('*Производитель*: ' . $this->message->name)
                ->line('*Сезон*: ' . self::SEASONS[$this->message->season])
                ->line('*Размер*: ' . $this->message->size)
                ->line('*Сумма*: ' . $this->message->income . ' BYN')
                ->line('*Документ*: ' . $this->message->url . ' BYN')
                ->button('*Документ*', $this->message->url);
        }
        return TelegramMessage::create()
            ->line('*Сервис*: ' . $this->message->place)
            ->line('*Смена*: ' . $this->message->username . ' и ' . $this->message->placed)
            ->line('*Продано*: ' . $this->message->amount . ' ед.')
            ->line('*Производитель*: ' . $this->message->name)
            ->line('*Сезон*: ' . self::SEASONS[$this->message->season])
            ->line('*Размер*: ' . $this->message->size)
            ->line('*Сумма*: ' . $this->message->income . ' BYN')
            ->button('*Документ*', $this->message->url);

    }

}
