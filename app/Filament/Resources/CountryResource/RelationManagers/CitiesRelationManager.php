<?php

declare(strict_types=1);

namespace App\Filament\Resources\CountryResource\RelationManagers;

use App\Rules\LatitudeRule;
use App\Rules\LongitudeRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

final class CitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'city';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lat')
                    ->rules([new LatitudeRule()])
                    ->numeric()
                    ->inputMode('decimal')
                    ->required(),
                Forms\Components\TextInput::make('long')
                    ->rules([new LongitudeRule()])
                    ->numeric()
                    ->inputMode('decimal')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('lat'),
                Tables\Columns\TextColumn::make('long'),
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
