<?php

namespace Makeable\LaravelEventStore;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class EventParser
{
    /**
     * @var object
     */
    public $event;

    /**
     * EventParser constructor.
     *
     * @param $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return get_object_vars($this->event);
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return array_map([$this, 'normalizeProperty'], $this->getProperties());
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return array_filter($this->getProperties(), function ($property) {
            return $property instanceof Eloquent;
        });
    }

    /**
     * @param $property
     * @return array
     */
    protected function normalizeProperty($property)
    {
        if ($property instanceof Arrayable) {
            return $property->toArray();
        }
        return $property;
    }
}