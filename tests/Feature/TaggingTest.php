<?php

namespace Makeable\LaravelEventStore\Tests\Feature;

use App\Events\UserRegistered;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelEventStore\Event;
use Makeable\LaravelEventStore\EventTag;
use Makeable\LaravelEventStore\Tests\TestCase;

class TaggingTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_recognises_eloquent_models_as_tags()
    {
        event(new UserRegistered($user = $this->user()));

        $this->assertEquals($user->id, Event::firstOrFail()->tags()->first()->related->id);
        $this->assertEquals($user->id, Event::firstOrFail()->related(User::class)->first()->id);
    }

    /** @test **/
    public function it_does_not_tag_none_eloquent_public_properties()
    {
        event(new UserRegistered($user = $this->user()));

        $this->assertEquals(1, EventTag::count());
    }
}
