<?php

namespace Makeable\LaravelEventStore;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EventTag extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function related()
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $query
     * @param $name
     * @param Model $model
     * @return Builder
     */
    public function scopeWhereMorph($query, $name, $model)
    {
        return $query
            ->where("{$name}_type", $model->getMorphClass())
            ->where("{$name}_id", $model->getKey());
    }
}
