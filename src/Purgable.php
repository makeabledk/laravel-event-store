<?php

namespace Makeable\LaravelEventStore;

interface Purgable
{
    /**
     * @param array $attributes
     * @return array
     */
    public function toPurgedArray($attributes);
}