<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('permission_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('model_for_permission_id')
                    ->required()
                    ->label('Model')
                    ->relationship('modelForPermission', 'model_name')
                    ->searchable('model_name')
                    ->preload(),
                Forms\Components\Toggle::make('is_viewer')
                    ->label('Is Viewer')
                    ->default(false),
                Forms\Components\Toggle::make('is_creator')
                    ->label('Is Creator')
                    ->default(false),
                Forms\Components\Toggle::make('is_updater')
                    ->label('Is Updater')
                    ->default(false),
                Forms\Components\Toggle::make('is_eraser')
                    ->label('Is Eraser')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('permission_name')
            ->columns([
                Tables\Columns\TextColumn::make('permission_name'),
                Tables\Columns\BooleanColumn::make('is_viewer')
                    ->label('Is Viewer'),
                Tables\Columns\BooleanColumn::make('is_creator')
                    ->label('Is Creator'),
                Tables\Columns\BooleanColumn::make('is_updater')
                    ->label('Is Updater'),
                Tables\Columns\BooleanColumn::make('is_eraser')
                    ->label('Is Eraser'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
