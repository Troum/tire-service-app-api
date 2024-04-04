<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public mixed $message;

    /**
     * Create a new event instance.
     */
    public function __construct(mixed $message)
    {
        $this->message = $message;
    }
}
