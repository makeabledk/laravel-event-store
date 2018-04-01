<?php

namespace Makeable\LaravelEventStore\Tests;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Makeable\LaravelEventStore\EventStoreServiceProvider;
use Makeable\LaravelEventStore\Tests\Stubs\User;

class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('APP_ENV=testing');
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');

        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $app->register(EventStoreServiceProvider::class);
        $app->afterResolving('migrator', function (Migrator $migrator) {
            $migrator->path(__DIR__.'/migrations/');
        });

        return $app;
    }

    /**
     * @param array $attributes
     * @return User
     */
    protected function user($attributes = [])
    {
        return User::create(factory(\App\User::class)->make($attributes)->getAttributes());
    }
}
