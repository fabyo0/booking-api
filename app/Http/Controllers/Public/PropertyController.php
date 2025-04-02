<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Requests\Property\ShowRequest;
use App\Http\Resources\PropertySearchResource;
use App\Models\Property;

final class PropertyController
{
    /**
     * Property Show
     */
    public function __invoke(Property $property, ShowRequest $request): PropertySearchResource
    {
        $with = [
            'city',
            'media',
            'apartments.apartment_type',
            'apartments.prices',
            'apartments.facilities.category',
        ];

        if ($request->adults && $request->children) {
            $property->load([
                'apartments' => function ($query) use ($request): void {
                    $query->where('capacity_adults', '>=', $request->adults)
                        ->where('capacity_children', '>=', $request->children)
                        ->orderBy('capacity_adults')
                        ->orderBy('capacity_children');
                },
                'apartments.facilities.category',
                'apartments.apartment_type',
                'apartments.prices',
                'city',
                'media',
            ]);
        } else {
            $property->load($with);
        }

        return new PropertySearchResource($property);
    }
}
