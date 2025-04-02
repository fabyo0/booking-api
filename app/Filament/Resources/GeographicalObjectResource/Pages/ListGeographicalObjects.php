<?php

declare(strict_types=1);

namespace App\Filament\Resources\GeographicalObjectResource\Pages;

use App\Filament\Resources\GeographicalObjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListGeographicalObjects extends ListRecords
{
    protected static string $resource = GeographicalObjectResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
