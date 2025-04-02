<?php

declare(strict_types=1);

namespace App\Filament\Resources\BedTypeResource\Pages;

use App\Filament\Resources\BedTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditBedType extends EditRecord
{
    protected static string $resource = BedTypeResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
