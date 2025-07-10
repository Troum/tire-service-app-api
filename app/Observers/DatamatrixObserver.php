<?php

namespace App\Observers;

use App\Models\Datamatrix;
use Illuminate\Support\Facades\Storage;

class DatamatrixObserver
{
    /**
     * Handle the Datamatrix "created" event.
     */
    public function created(Datamatrix $datamatrix): void
    {
        //
    }

    /**
     * Handle the Datamatrix "updated" event.
     */
    public function updated(Datamatrix $datamatrix): void
    {
        //
    }

    /**
     * Handle the Datamatrix "deleted" event.
     */
    public function deleted(Datamatrix $datamatrix): void
    {
        Storage::delete(storage_path('app/public/datamatrix/' . $datamatrix->id . '.zip'));
    }

    /**
     * Handle the Datamatrix "restored" event.
     */
    public function restored(Datamatrix $datamatrix): void
    {
        //
    }

    /**
     * Handle the Datamatrix "force deleted" event.
     */
    public function forceDeleted(Datamatrix $datamatrix): void
    {
        //
    }
}
