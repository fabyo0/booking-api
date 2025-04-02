<?php

declare(strict_types=1);

namespace App\Filament\Resources\BedTypeResource\Pages;

use App\Filament\Resources\BedTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListBedTypes extends ListRecords
{
    protected static string $resource = BedTypeResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
