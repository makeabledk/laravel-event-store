<?php

namespace Makeable\LaravelEventStore;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Set created at attribute.
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(EventTag::class);
    }

    /**
     * @param $class
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function related($class)
    {
        return $this->morphedByMany($class, 'related', 'event_tags', 'event_id')
            ->withTimestamps()
            ->withPivot('name');
    }
}
