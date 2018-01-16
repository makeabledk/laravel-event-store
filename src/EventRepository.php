<?php

namespace Makeable\LaravelEventStore;

class EventRepository
{
    /**
     * @param $event
     * @param null $name
     * @return Event
     */
    public function save($event, $name = null)
    {
        return $this->saveTags(Event::create([
            'name' => $name ?: get_class($event),
            'payload' => ($parser = new EventParser($event))->getPayload()
        ]), $parser->getTags());
    }

    /**
     * @param Event $event
     * @param $tags
     * @return Event
     */
    protected function saveTags(Event $event, $tags)
    {
        collect($tags)->each(function ($related, $name) use ($event) {
            $tag = new EventTag;
            $tag->name = $name;
            $tag->event()->associate($event);
            $tag->related()->associate($related);
            $tag->save();
        });

        return $event;
    }
}