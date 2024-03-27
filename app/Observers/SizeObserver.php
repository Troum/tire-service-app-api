<?php

namespace App\Observers;

use App\Events\UpdateSizeListEvent;
use App\Models\Size;

class SizeObserver
{
    /**
     * Handle the Size "created" event.
     */
    public function created(Size $size): void
    {
        broadcast(new UpdateSizeListEvent(Size::select(['id', 'size'])->get()->toArray()));
    }

    /**
     * Handle the Size "updated" event.
     */
    public function updated(Size $size): void
    {
        //
    }

    /**
     * Handle the Size "deleted" event.
     */
    public function deleted(Size $size): void
    {
        //
    }

    /**
     * Handle the Size "restored" event.
     */
    public function restored(Size $size): void
    {
        //
    }

    /**
     * Handle the Size "force deleted" event.
     */
    public function forceDeleted(Size $size): void
    {
        //
    }
}
