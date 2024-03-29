<?php

namespace Makeable\LaravelEventStore\Tests\Stubs;

use Makeable\LaravelEventStore\Purgable;
use Makeable\LaravelEventStore\PurgesAttributesOnDelete;

class User extends \App\Models\User implements Purgable
{
    use PurgesAttributesOnDelete;

    /**
     * @var callable
     */
    public $purgePolicy;

    /**
     * @return User
     */
    public function mockSoftDeletion()
    {
        return $this->fireModelEvent('deleted', false);
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function toPurgedArray($attributes)
    {
        return call_user_func($this->purgePolicy, $attributes);
    }
}
