<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApartmentResource\Pages;

use App\Filament\Resources\ApartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListApartments extends ListRecords
{
    protected static string $resource = ApartmentResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
