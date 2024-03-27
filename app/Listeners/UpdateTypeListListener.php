<?php

namespace App\Listeners;

use App\Events\UpdateTypeListEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTypeListListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UpdateTypeListEvent $event): void
    {
        //
    }
}
