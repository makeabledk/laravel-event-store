<?php

namespace Makeable\LaravelEventStore;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventStoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! class_exists('LaravelEventStoreCreateEventsTable') &&
            ! class_exists('CreateEventsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_events_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_events_table.php'),
                __DIR__.'/../database/migrations/create_event_tags_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time() + 1).'_create_event_tags_table.php'),
            ], 'migrations');
        }

        $this->mergeConfigFrom(__DIR__.'/../config/laravel-event-store.php', 'laravel-event-store');
        $this->publishes([__DIR__.'/../config/laravel-event-store.php' => config_path('laravel-event-store.php')], 'config');

        Event::listen(Config::get('laravel-event-store.log'), function ($name, $payload) {
            $this->app->make(EventRepository::class)->save($payload[0], $name);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EventRepository::class);
    }
}
