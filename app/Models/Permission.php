<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'permission_name',
        'model_for_permission_id',
        'is_viewer',
        'is_creator',
        'is_updater',
        'is_eraser'
    ];

    protected $casts = [
        'is_viewer' => 'boolean',
        'is_creator' => 'boolean',
        'is_updater' => 'boolean',
        'is_eraser' => 'boolean'
    ];

    public function ModelForPermission(): BelongsTo
    {
        return $this->belongsTo(ModelForPermission::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasModel(string $model): bool
    {
        return $this->ModelForPermission->model_path === $model;
    }
}
