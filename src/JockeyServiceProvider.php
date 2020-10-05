<?php

namespace Sammyjo20\Jockey;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class JockeyServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateOnlineMailablesTable')) {
                $this->publishes([
                    __DIR__ . '/../stubs/migrations/create_online_mailables_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_online_mailables_table.php'),
                ], 'jockey-migrations');
            }

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/jockey'),
            ], 'jockey-views');
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'jockey');

        Event::listen([
            MessageSending::class => [
                CreateOnlineMailable::class,
                AppendOnlineMailableUrl::class,
            ],
        ]);
    }
}
