<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);

        $this->user = $user;
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function getUserByEmail(string $email)
    {
        return $this->user::where('email', strtolower(trim($email)))->firstOrFail();
    }
}
