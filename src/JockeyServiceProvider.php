<?php

namespace Sammyjo20\Jockey;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Sammyjo20\Jockey\Commands\DeleteExpiredMailables;

class JockeyServiceProvider extends BaseServiceProvider
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
                __DIR__ . '/../resources/views' => resource_path('views/vendor/jockey'),
            ], 'jockey-views');

            $this->publishes([
                __DIR__ . '/../config/jockey.php' => config_path('jockey.php'),
            ], 'jockey-config');

            if (!class_exists('CreateOnlineMailablesTable')) {
                $this->publishes([
                    __DIR__ . '/../stubs/migrations/create_online_mailables_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_online_mailables_table.php'),
                ], 'jockey-migrations');
            }
        }

        return $this;
    }

    private function registersEventListeners(): self
    {
        $this->app->register(JockeyEventServiceProvider::class);

        return $this;
    }

    private function loadConfig(): self
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/jockey.php', 'jockey'
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'jockey');

        return $this;
    }

    private function loadTranslations(): self
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'jockey');

        return $this;
    }

    private function scheduleCommands(): self
    {
        $this->commands($this->commands);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('jockey:delete-expired-mailables')->everyMinute();
        });

        return $this;
    }
}
