<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApartmentResource\Pages;

use App\Filament\Resources\ApartmentResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateApartment extends CreateRecord
{
    protected static string $resource = ApartmentResource::class;
}
