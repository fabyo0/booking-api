<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApartmentTypeResource\Pages;

use App\Filament\Resources\ApartmentTypeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateApartmentType extends CreateRecord
{
    protected static string $resource = ApartmentTypeResource::class;
}
