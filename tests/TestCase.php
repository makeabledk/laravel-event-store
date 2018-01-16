<?php

namespace Makeable\LaravelEventStore\Tests;

use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use DB;
use Makeable\LaravelEventStore\EventStoreServiceProvider;

class TestCase extends BaseTestCase
{
//    public function setUp()
//    {
//        parent::setUp();
//
//    }

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

        return $app;
    }

    /**
     * @return User
     */
    protected function user()
    {
        return factory(User::class)->create();
    }
}