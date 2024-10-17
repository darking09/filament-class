<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PostStatus: String implements HasLabel
{
    case DRAFT = 'draft';
    case PUBLISH = 'publish';
    case PENDING = 'pending';
    case PRIVATE = 'private';
    case FUTURE = 'future';
    case REVIEW = 'review';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISH => 'Publish',
            self::PENDING => 'Pending',
            self::PRIVATE => 'Private',
            self::FUTURE => 'Future',
            self::REVIEW => 'Review',
        };
    }
}
