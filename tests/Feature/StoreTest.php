<?php

namespace Makeable\LaravelEventStore\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelEventStore\Event;
use Makeable\LaravelEventStore\Tests\Stubs\UserRegistered;
use Makeable\LaravelEventStore\Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_stores_dispatched_events_in_database()
    {
        event(new UserRegistered($this->user()));

        $this->assertEquals(UserRegistered::class, Event::firstOrFail()->name);
    }

    /** @test **/
    public function it_serializes_events_correctly()
    {
        event(new UserRegistered($user = $this->user()));

        $this->assertEquals(['user' => $user->toArray(), 'foo' => 'bar'], Event::first()->payload);
    }
}
