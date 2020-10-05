<?php

namespace Sammyjo20\Jockey;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as EventServiceProvider;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Sammyjo20\Jockey\Listeners\AppendOnlineMailableUrl;
use Sammyjo20\Jockey\Listeners\CreateOnlineMailable;

class JockeyEventServiceProvider extends EventServiceProvider
{
    /**
     * Register our event listeners for Laravel's built in
     * mail message events.
     *
     * @var array[]
     */
    protected $listen = [
        MessageSending::class => [
            AppendOnlineMailableUrl::class,
        ],
        MessageSent::class => [
            CreateOnlineMailable::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
