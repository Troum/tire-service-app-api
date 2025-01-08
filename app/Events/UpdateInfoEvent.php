<?php

namespace App\Events;

use App\Models\Info;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateInfoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int $id
     */
    public int $id;

    /**
     * Create a new event instance.
     */
    public function __construct(int $id)
    {   /** @var array|Info[] $info */
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('info.' . $this->id . '.update'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'updated.info';
    }

    /**
     * @return string
     */
    public function broadcastQueue(): string
    {
        return 'reverb';
    }
}
