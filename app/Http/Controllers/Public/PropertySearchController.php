<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\SearchRequest;
use App\Http\Resources\PropertySearchResource;
use App\Models\Geoobject;
use App\Models\Property;

final class PropertySearchController extends Controller
{
    /**
     *Property Search
     * */
    public function __invoke(SearchRequest $request)
    {
        $properties = Property::with([
            'city', 'apartments.apartment_type',
            'apartments.rooms.beds.bed_type',
        ])
            // Search city
            ->when($request->city, function ($query) use ($request): void {
                $query->where('city_id', $request->city);
            })
            // Search country
            ->when($request->country, function ($query) use ($request): void {
                $query->whereHas('city', fn ($q) => $q->where('country_id', $request->country));
            })
            //TODO: Properties within 10 km
            ->when($request->geoobject, function ($query) use ($request): void {
                $geoobject = Geoobject::find($request->geoobject);
                if ($geoobject) {
                    $condition = '(
                        6371 * acos(
                            cos(radians('.$geoobject->lat.'))
                            * cos(radians(`lat`))
                            * cos(radians(`long`) - radians('.$geoobject->long.'))
                            + sin(radians('.$geoobject->lat.')) * sin(radians(`lat`))
                        ) < 10
                    )';
                    $query->whereRaw($condition);
                }
                //TODO: Apartment Filter children & adults
            })->when($request->adults && $request->children, callback: function ($query) use ($request): void {
                $query->withWhereHas(relation: 'apartments', callback: function ($query) use ($request): void {
                    $query->where('capacity_adults', '>=', $request->adults)
                        ->where('capacity_children', '>=', $request->children)
                        ->orderBy('capacity_adults')
                        ->orderBy('capacity_children')
                        ->take(1);
                });
            })->get();

        return PropertySearchResource::collection($properties);
    }
}
