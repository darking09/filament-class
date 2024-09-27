<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy extends AccessPolicy
{
    protected $modelClass = Role::class;
}
