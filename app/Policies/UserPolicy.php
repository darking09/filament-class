<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy extends AccessPolicy
{
    protected $modelClass = User::class;
}
