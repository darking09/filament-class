<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use App\Models\PostType;
use App\Enums\PostStatus;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use \Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Config;

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
                Section::make('Post Information')
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
                        Forms\Components\RichEditor::make('content')
                            ->disableToolbarButtons(['codeBlock'])
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpan([
                        'md' => 3,
                        '2xl' => 6,
                    ]),
                    Group::make()->schema([
                        Section::make('Post status')
                        ->schema([
                            Forms\Components\Select::make('status')
                                ->required()
                                ->label('Status')
                                ->options(PostStatus::class)
                                ->default(PostStatus::PUBLISH)
                                ->live(),
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->dehydrated(fn($state) => filled($state))
                                ->maxLength(255)
                                ->autocomplete('new-password')
                                ->revealable()
                                ->visible(fn(GET $get): bool => Post::checkStatus($get('status'), PostStatus::PRIVATE))
                                ->required(fn(Post $post): bool => empty($post->password)),
                            Forms\Components\DateTimePicker::make('published_at')
                                ->required()
                                ->label('Published At')
                                ->visible(fn(GET $get): bool => Post::checkStatus($get('status'), PostStatus::FUTURE)),
                        ]),
                        Section::make('Post Type Information')
                            ->schema([
                                Forms\Components\Select::make('post_type_id')
                                ->required()
                                ->label('Post Type')
                                ->relationship('postType', 'name')
                                ->searchable('name')
                                ->default(function(PostType $post) {
                                    return $post::all()->first()->id;
                                })
                                ->preload()
                                ->columnSpan([
                                    'sm' => 1,
                                    'md' => 2,
                                    '2xl' => 4,
                                ]),
                            ])
                            ->collapsed(),
                        Section::make('Post Information')
                            ->schema([
                                Forms\Components\TextArea::make('excerpt')
                                    ->maxLength(255)
                                    ->autosize(),
                                Forms\Components\FileUpload::make('featured_image')
                                    ->imageResizeMode('cover')
                            ])
                            ->collapsed(),
                        Section::make('Tags Information')
                            ->schema([
                                Forms\Components\Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->searchable('name')
                                    ->preload()
                                    ->multiple()
                            ])
                            ->collapsed(),
                        Section::make('Categories Information')
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->searchable('name')
                                    ->preload()
                                    ->multiple()
                            ])
                            ->collapsed(),
                    ])
                    ->columnSpan([
                        'md' => 1,
                        '2xl' => 2,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->datetime()
                    ->sortable()
                    ->since(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->default('No tags assigned yet')
                    ->color(function (string $state): string {
                        $colors = Config::get('constants.COLORS');

                        if ($state === 'No tags assigned yet') {
                            return 'danger';
                        }

                        shuffle($colors);

                        return $colors[0];

                    }),
                Tables\Columns\TextColumn::make('categories.name')
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->default('No categories assigned yet')
                    ->color(function (string $state): string {
                        $colors = Config::get('constants.COLORS');

                        if ($state === 'No categories assigned yet') {
                            return 'danger';
                        }

                        shuffle($colors);

                        return $colors[0];

                    })
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
