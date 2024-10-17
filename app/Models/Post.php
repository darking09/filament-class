<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PostStatus;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'post_type_id',
        'user_id',
        'status',
        'published_at',
        'password',
        'excerpt',
        'featured_image'
    ];

    protected $hidden = [
        'password'
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'status' => PostStatus::class,
            'password' => 'hashed'
        ];
    }
    public function postType(): BelongsTo
    {
        return $this->belongsTo(PostType::class);
    }

    static function checkStatus($status, $valueEnum): bool
    {
        return $status === $valueEnum || $status === $valueEnum->value;
    }
}
