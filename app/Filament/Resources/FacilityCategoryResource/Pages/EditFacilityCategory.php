<?php

declare(strict_types=1);

namespace App\Filament\Resources\FacilityCategoryResource\Pages;

use App\Filament\Resources\FacilityCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditFacilityCategory extends EditRecord
{
    protected static string $resource = FacilityCategoryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
