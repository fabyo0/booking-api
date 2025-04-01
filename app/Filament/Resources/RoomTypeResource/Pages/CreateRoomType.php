<?php

declare(strict_types=1);

namespace App\Filament\Resources\RoomTypeResource\Pages;

use App\Filament\Resources\RoomTypeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateRoomType extends CreateRecord
{
    protected static string $resource = RoomTypeResource::class;
}
