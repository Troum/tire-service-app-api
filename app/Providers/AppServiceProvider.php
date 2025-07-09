<?php

namespace App\Providers;

use App\Events\DatamatrixCreatedEvent;
use App\Events\OrderEvent;
use App\Events\UpdateInfoEvent;
use App\Events\UpdateSizeListEvent;
use App\Events\UpdateTypeListEvent;
use App\Listeners\DatamatrixListener;
use App\Listeners\OrderListener;
use App\Listeners\UpdateInfoListener;
use App\Listeners\UpdateSizeListListener;
use App\Listeners\UpdateTypeListListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            UpdateSizeListEvent::class,
            UpdateSizeListListener::class,
        );

        Event::listen(
            UpdateTypeListEvent::class,
            UpdateTypeListListener::class,
        );

        Event::listen(
            UpdateInfoEvent::class,
            UpdateInfoListener::class,
        );

        Event::listen(
            DatamatrixCreatedEvent::class,
            DatamatrixListener::class,
        );
    }
}
