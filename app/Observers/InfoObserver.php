<?php

namespace App\Observers;

use App\Events\UpdateInfoListEvent;
use App\Models\Info;

class InfoObserver
{
    /**
     * Handle the Info "created" event.
     */
    public function created(Info $info): void
    {
        //
    }

    /**
     * Handle the Info "updated" event.
     */
    public function updated(Info $info): void
    {
        broadcast(new UpdateInfoListEvent());
    }

    /**
     * Handle the Info "deleted" event.
     */
    public function deleted(Info $info): void
    {
        //
    }

    /**
     * Handle the Info "restored" event.
     */
    public function restored(Info $info): void
    {
        //
    }

    /**
     * Handle the Info "force deleted" event.
     */
    public function forceDeleted(Info $info): void
    {
        //
    }
}
