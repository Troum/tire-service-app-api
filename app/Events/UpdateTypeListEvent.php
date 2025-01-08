<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTypeListEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $types;

    /**
     * Create a new event instance.
     */
    public function __construct(array $types)
    {
        $this->types = $types;
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
        return $this->types;
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'update.type.list';
    }

    /**
     * @return string
     */
    public function broadcastQueue(): string
    {
        return 'reverb';
    }
}
