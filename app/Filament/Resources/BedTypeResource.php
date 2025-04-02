<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BedTypeResource\Pages;
use App\Models\BedType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Override;

final class BedTypeResource extends Resource
{
    protected static ?string $model = BedType::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Facilities';

    #[Override]
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
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

        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBedTypes::route('/'),
            'create' => Pages\CreateBedType::route('/create'),
            'edit' => Pages\EditBedType::route('/{record}/edit'),
        ];
    }
}
