<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateSizeListEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public array $sizes;

    /**
     * Create a new event instance.
     */
    public function __construct(array $sizes)
    {
        $this->sizes = $sizes;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('update'),
        ];
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        return $this->sizes;
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'update.size.list';
    }
}
