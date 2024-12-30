<?php

namespace App\Filament\Resources\GeographicalObjectResource\Pages;

use App\Filament\Resources\GeographicalObjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeographicalObjects extends ListRecords
{
    protected static string $resource = GeographicalObjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
