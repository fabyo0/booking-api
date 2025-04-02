<?php

declare(strict_types=1);

namespace App\Filament\Resources\RoomTypeResource\Pages;

use App\Filament\Resources\RoomTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditRoomType extends EditRecord
{
    protected static string $resource = RoomTypeResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
