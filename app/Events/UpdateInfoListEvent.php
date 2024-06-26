<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateInfoListEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $infos;

    /**
     * Create a new event instance.
     */
    public function __construct(array $infos)
    {
        $this->infos = $infos;
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
        return $this->infos;
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'update.info.list';
    }
}
