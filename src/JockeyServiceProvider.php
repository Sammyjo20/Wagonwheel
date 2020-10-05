<?php

namespace Sammyjo20\Jockey;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Sammyjo20\Jockey\Exceptions\OnlineMailablePendingException;
use Sammyjo20\Jockey\Models\OnlineMailable;

class JockeyServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->register(JockeyEventServiceProvider::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (!class_exists('CreateOnlineMailablesTable')) {
                $this->publishes([
                    __DIR__ . '/../stubs/migrations/create_online_mailables_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_online_mailables_table.php'),
                ], 'migrations');
            }
        }

        $this->loadRoutesFrom(__DIR__ . '/Http/router.php');

        $this->loadViewsFrom(__DIR__ . '/Views/', 'jockey');
    }
}
