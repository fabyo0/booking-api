<?php

declare(strict_types=1);

namespace App\Filament\Resources\BedTypeResource\Pages;

use App\Filament\Resources\BedTypeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateBedType extends CreateRecord
{
    protected static string $resource = BedTypeResource::class;
}
