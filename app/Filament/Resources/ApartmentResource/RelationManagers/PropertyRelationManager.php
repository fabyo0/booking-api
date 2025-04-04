<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApartmentResource\RelationManagers;

use App\Rules\LatitudeRule;
use App\Rules\LongitudeRule;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Override;

final class PropertyRelationManager extends RelationManager
{
    protected static string $relationship = 'property';

    #[Override]
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('address_street')
                    ->required(),
                Forms\Components\TextInput::make('address_postcode')
                    ->required(),
                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'name')
                    ->preload()
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('lat')
                    ->required()
                    ->rules([new LatitudeRule()]),
                Forms\Components\TextInput::make('long')
                    ->required()
                    ->rules([new LongitudeRule()]),
                Forms\Components\Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->required()
                    ->searchable(),
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->image()
                    ->maxSize(5000)
                    ->multiple()
                    ->columnSpanFull()
                    ->collection('avatars')
                    ->conversion('thumbnail'),
            ]);
    }

    #[Override]
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('owner.name'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('city.name'),
            ])
            ->filters([

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
