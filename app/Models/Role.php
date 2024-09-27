<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_name'
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    public function checkIfHasPermission(string $model): Array
    {
        $myPermissions = [];

        foreach ($this->permissions as $permission) {
            if ($permission->hasModel($model)) {
                $myPermissions[] = $permission;
            }
        }

        return $myPermissions;
    }
}
