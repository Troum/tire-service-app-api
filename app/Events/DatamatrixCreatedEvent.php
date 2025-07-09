<?php

namespace App\Events;

use App\Models\Datamatrix;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DatamatrixCreatedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Datamatrix $datamatrix)
    {
        //
    }
}
