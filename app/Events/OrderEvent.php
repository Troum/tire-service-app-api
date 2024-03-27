<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public mixed $order;
    public string $username;
    public ?string $placedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(mixed $order, string $username, mixed $placedBy)
    {
        $this->order = $order;
        $this->username = $username;
        $this->placedBy = $placedBy;
    }
}
