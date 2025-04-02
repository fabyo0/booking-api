<?php

declare(strict_types=1);

namespace App\Filament\Resources\FacilityCategoryResource\Pages;

use App\Filament\Resources\FacilityCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListFacilityCategories extends ListRecords
{
    protected static string $resource = FacilityCategoryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
