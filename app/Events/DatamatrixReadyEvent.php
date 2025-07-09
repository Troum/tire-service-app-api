<?php

namespace App\Events;

use App\Models\Datamatrix;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DatamatrixReadyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Datamatrix $datamatrix)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('datamatrix.' . $this->datamatrix->id),
        ];
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id'  => $this->datamatrix->id,
            'url' => $this->datamatrix->url,
        ];
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'datamatrix.created';
    }

    /**
     * @return string
     */
    public function broadcastQueue(): string
    {
        return 'reverb';
    }
}
