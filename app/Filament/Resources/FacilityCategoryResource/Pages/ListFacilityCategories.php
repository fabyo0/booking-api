<?php

namespace App\Filament\Resources\FacilityCategoryResource\Pages;

use App\Filament\Resources\FacilityCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFacilityCategories extends ListRecords
{
    protected static string $resource = FacilityCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
