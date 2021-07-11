<?php

namespace Makeable\LaravelEventStore\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Makeable\LaravelEventStore\Event;
use Makeable\LaravelEventStore\EventRepository;
use Makeable\LaravelEventStore\Tests\Stubs\User;
use Makeable\LaravelEventStore\Tests\Stubs\UserRegistered;
use Makeable\LaravelEventStore\Tests\TestCase;

class PurgeTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_can_purge_tagged_event_data()
    {
        event(new UserRegistered($user = $this->user()));

        $payload = Event::first()->payload['user'];

        $this->assertEquals($user->toArray(), $payload);
        $this->assertNotNull($payload['name']);

        $user->purgePolicy = function ($attributes) {
            return Arr::only($attributes, 'id');
        };

        app(EventRepository::class)->purge($user);

        $payload = Event::first()->payload['user'];

        $this->assertEquals(1, $payload['id']);
        $this->assertArrayNotHasKey('name', $payload);
    }

    /** @test **/
    public function it_can_purge_events_on_observed_deletion()
    {
        event(new UserRegistered($user = $this->user()));

        $this->assertNotNull(Event::first()->payload['user']['id']);

        $user->purgePolicy = function ($attributes) {
            return array_merge($attributes, ['id' => null]);
        };

        $user->delete();

        $this->assertEquals(0, User::count());
        $this->assertNull(Event::first()->payload['user']['id']);
        $this->assertNotNull(Event::first()->payload['user']['name']);
    }

    /** @test **/
    public function it_can_purge_model_on_observed_deletion()
    {
        event(new UserRegistered($user = $this->user()));

        $this->assertNotNull($user->name);

        $user->purgePolicy = function ($attributes) {
            return array_merge($attributes, ['name' => '', 'password' => '']);
        };

        $user->mockSoftDeletion();

        $this->assertEquals(1, $user->id);
        $this->assertEmpty($user->name);
        $this->assertEmpty($user->password);
    }
}
