<?php

namespace App\Observers;

use App\Events\UpdateUserListEvent;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        broadcast(new UpdateUserListEvent(User::select(['id', 'name', 'email'])->with('history', 'roles')->get()->toArray()));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        broadcast(new UpdateUserListEvent(User::select(['id', 'name', 'email'])->with('history', 'roles')->get()->toArray()));
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
