<?php

namespace App\Observers;

use App\Events\UpdatePlaceListEvent;
use App\Models\Place;

class PlaceObserver
{
    /**
     * Handle the Place "created" event.
     */
    public function created(Place $place): void
    {
        broadcast(new UpdatePlaceListEvent(Place::all()->toArray()));
    }

    /**
     * Handle the Place "updated" event.
     */
    public function updated(Place $place): void
    {
        //
    }

    /**
     * Handle the Place "deleted" event.
     */
    public function deleted(Place $place): void
    {
        //
    }

    /**
     * Handle the Place "restored" event.
     */
    public function restored(Place $place): void
    {
        //
    }

    /**
     * Handle the Place "force deleted" event.
     */
    public function forceDeleted(Place $place): void
    {
        //
    }
}
