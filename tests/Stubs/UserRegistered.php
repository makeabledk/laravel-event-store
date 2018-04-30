<?php

namespace Makeable\LaravelEventStore\Tests\Stubs;

use App\User;
use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use SerializesModels;

    /**
     * @var User
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
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
