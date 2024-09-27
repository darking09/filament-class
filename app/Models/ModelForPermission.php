<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ModelForPermission extends Model
{
    use HasFactory;

    public function permissions(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
