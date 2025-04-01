<?php

declare(strict_types=1);

namespace App\Filament\Resources\GeographicalObjectResource\Pages;

use App\Filament\Resources\GeographicalObjectResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateGeographicalObject extends CreateRecord
{
    protected static string $resource = GeographicalObjectResource::class;
}
