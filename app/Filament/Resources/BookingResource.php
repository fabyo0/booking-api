<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Override;

final class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'Booking';

    #[Override]
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('apartment_id')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->native(false)
                    ->relationship(name: 'apartment', titleAttribute: 'name'),
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->relationship(name: 'user', titleAttribute: 'name'),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\TextInput::make('guests_adults')
                    ->integer()
                    ->required()
                    ->minValue(0),
                Forms\Components\TextInput::make('guests_children')
                    ->integer()
                    ->required()
                    ->minValue(0),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('apartment.name'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Cancel'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Cancel'),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
