<?php

declare(strict_types=1);

namespace App\Filament\Resources\FacilityCategoryResource\Pages;

use App\Filament\Resources\FacilityCategoryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateFacilityCategory extends CreateRecord
{
    protected static string $resource = FacilityCategoryResource::class;
}
