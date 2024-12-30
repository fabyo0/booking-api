<?php

namespace App\Filament\Resources\ApartmentTypeResource\Pages;

use App\Filament\Resources\ApartmentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApartmentType extends EditRecord
{
    protected static string $resource = ApartmentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
