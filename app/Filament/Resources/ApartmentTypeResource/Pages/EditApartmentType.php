<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApartmentTypeResource\Pages;

use App\Filament\Resources\ApartmentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditApartmentType extends EditRecord
{
    protected static string $resource = ApartmentTypeResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
