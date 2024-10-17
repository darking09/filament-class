<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Enums\PostStatus;
use App\Models\Post;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

        return $data;
    }
}
