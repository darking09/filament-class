<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Enums\PostStatus;
use App\Models\Post;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        if (!isset($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
        }

        if(!Post::checkStatus($data['status'], PostStatus::PRIVATE)) {
            $data['password'] = null;
        }

        if(Post::checkStatus($data['status'], PostStatus::PUBLISH)) {
            $data['published_at'] = now();
        } else if(!Post::checkStatus($data['status'], PostStatus::FUTURE)){
            $data['published_at'] = null;
        }

        $data['user_id'] = auth()->id();

        return $data;
    }
}
