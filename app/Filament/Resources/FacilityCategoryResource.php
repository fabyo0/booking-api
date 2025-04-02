<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityCategoryResource\Pages;
use App\Filament\Resources\FacilityCategoryResource\RelationManagers;
use App\Models\FacilityCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Override;

final class FacilityCategoryResource extends Resource
{
    protected static ?string $model = FacilityCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Facilities';

    #[Override]
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([

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

    #[Override]
    public static function getRelations(): array
    {
        return [
            RelationManagers\FacilitiesRelationManager::class,
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacilityCategories::route('/'),
            'create' => Pages\CreateFacilityCategory::route('/create'),
            'edit' => Pages\EditFacilityCategory::route('/{record}/edit'),
        ];
    }
}
