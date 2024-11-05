<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentDetailsResource;
use App\Models\Apartment;

final class ApartmentController extends Controller
{
    public function __invoke(Apartment $apartment): ApartmentDetailsResource
    {
        $apartment->load('facilities');

        //TODO: Facility category group
        $apartment->setAttribute(
            'facility_categories',
            $apartment->facilities->groupBy('category.name')
                ->mapWithKeys(fn ($items, $key) => [$key => $items->pluck('name')])
        );

        return new ApartmentDetailsResource($apartment);
    }
}
