<?php

namespace Makeable\LaravelEventStore\Tests\Stubs;

use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use SerializesModels;

    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var string
     */
    public $foo = 'bar';

    /**
     * @var string
     */
    protected $password = 'secret';

    /**
     * UserRegistered constructor.
     *
     * @param User $user
     */
    public function __construct(\App\Models\User $user)
    {
        $this->user = $user;
    }
}
