<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApartmentTypeResource\Pages;

use App\Filament\Resources\ApartmentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListApartmentTypes extends ListRecords
{
    protected static string $resource = ApartmentTypeResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
