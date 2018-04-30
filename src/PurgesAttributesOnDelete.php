<?php

namespace Makeable\LaravelEventStore;

use Illuminate\Database\Eloquent\Model;

trait PurgesAttributesOnDelete
{
    public static function bootPurgesAttributesOnDelete()
    {
        static::deleted(function (Model $model) {
            app(EventRepository::class)->purge($model);

            if ($fresh = $model->fresh()) {
                $model->forceFill($model->toPurgedArray($model->getMutatedAttributes()))->save();
            }
        });
    }
}
