<?php

namespace App\Observers;

use App\Events\UpdateTypeListEvent;
use App\Models\Type;

class TypeObserver
{
    /**
     * Handle the Type "created" event.
     */
    public function created(Type $type): void
    {
        broadcast(new UpdateTypeListEvent(Type::select(['id', 'size_id', 'type', 'season'])->get()->toArray()));
    }

    /**
     * Handle the Type "updated" event.
     */
    public function updated(Type $type): void
    {
        broadcast(new UpdateTypeListEvent(Type::select(['id', 'size_id', 'type', 'season'])->with(['size', 'info'])->get()->toArray()));
    }

    /**
     * Handle the Type "deleted" event.
     */
    public function deleted(Type $type): void
    {
        //
    }

    /**
     * Handle the Type "restored" event.
     */
    public function restored(Type $type): void
    {
        //
    }

    /**
     * Handle the Type "force deleted" event.
     */
    public function forceDeleted(Type $type): void
    {
        //
    }
}
