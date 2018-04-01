<?php

namespace Makeable\LaravelEventStore;

use Illuminate\Database\Eloquent\Model;

class EventRepository
{
    /**
     * @param Purgable $model
     * @return $this
     */
    public function purge(Purgable $model)
    {
        EventTag::with('event')
            ->whereMorph('related', $model)
            ->get()
            ->each(function (EventTag $tag) use ($model) {
                $payload = $tag->event->payload;
                $payload[$tag->name] = $model->toPurgedArray($payload[$tag->name]);
                $tag->event->update(compact('payload'));
            });

        return $this;
    }

    /**
     * @param $event
     * @param null $name
     * @return Event
     */
    public function save($event, $name = null)
    {
        return $this->saveTags(Event::create([
            'name' => $name ?: get_class($event),
            'payload' => ($parser = new EventParser($event))->getPayload(),
        ]), $parser->getTags());
    }

    // _________________________________________________________________________________________________________________

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
