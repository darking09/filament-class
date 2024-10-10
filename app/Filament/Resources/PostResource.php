<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'gmdi-post-add-o';

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 1,
                'md' => 4,
                '2xl' => 8,
            ])
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 3,
                        '2xl' => 5,
                    ]),
                Forms\Components\TextInput::make('slug')
                    ->visible(fn($operation) => $operation !== 'create')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2,
                        '2xl' => 4,
                    ]),
                Forms\Components\Select::make('post_type_id')
                    ->required()
                    ->label('Post Type')
                    ->relationship('postType', 'name')
                    ->searchable('name')
                    ->default('', fn ($resource) => $resource->getRelatedResource('postType')->getModel()::first()?->getKey())
                    ->preload()
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2,
                        '2xl' => 4,
                    ]),
                Forms\Components\RichEditor::make('content')
                    ->disableToolbarButtons(['codeBlock'])
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
