<?php

namespace Sammyjo20\Wagonwheel;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Sammyjo20\Wagonwheel\Commands\DeleteExpiredMailables;

class WagonwheelServiceProvider extends BaseServiceProvider
{
    protected $commands = [
        DeleteExpiredMailables::class,
    ];

    public function register()
    {
        $this->registersEventListeners()
            ->loadConfig();
    }

    public function boot(): void
    {
        $this->publishesItems()
            ->loadConfig()
            ->loadViews()
            ->loadRoutes()
            ->loadTranslations();

        if ($this->app->runningInConsole()) {
            $this->scheduleCommands();
        }
    }

    private function publishesItems(): self
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/Wagonwheel'),
            ], 'wagonwheel-views');

            $this->publishes([
                __DIR__ . '/../config/Wagonwheel.php' => config_path('Wagonwheel.php'),
            ], 'wagonwheel-config');

            if (!class_exists('CreateOnlineMailablesTable')) {
                $this->publishes([
                    __DIR__ . '/../stubs/migrations/create_online_mailables_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_online_mailables_table.php'),
                ], 'wagonwheel-migrations');
            }
        }

        return $this;
    }

    private function registersEventListeners(): self
    {
        $this->app->register(WagonwheelEventServiceProvider::class);

        return $this;
    }

    private function loadConfig(): self
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/wagonwheel.php', 'wagonwheel'
        );

        return $this;
    }

    private function loadRoutes(): self
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        return $this;
    }

    private function loadViews(): self
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'wagonwheel');

        return $this;
    }

    private function loadTranslations(): self
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'wagonwheel');

        return $this;
    }

    private function scheduleCommands(): self
    {
        $this->commands($this->commands);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('wagonwheel:delete-expired-mailables')->everyMinute();
        });

        return $this;
    }
}
