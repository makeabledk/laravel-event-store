<?php

namespace Makeable\LaravelEventStore;

use Illuminate\Support\Facades\Facade;

class EventStoreFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return EventRepository::class;
    }
}
