<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\SearchRequest;
use App\Http\Resources\PropertySearchResource;
use App\Models\Facility;
use App\Models\Geoobject;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;

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
            'facilities',
            'media' => fn($query) => $query->orderBy('position'),
        ])
            // Search city
            ->when($request->city, function ($query) use ($request): void {
                $query->where('city_id', $request->city);
            })
            // Search country
            ->when($request->country, function ($query) use ($request): void {
                $query->whereHas('city', fn($q) => $q->where('country_id', $request->country));
            })
            //TODO: Properties within 10 km
            ->when($request->geoobject, function ($query) use ($request): void {
                $geoobject = Geoobject::find($request->geoobject);
                if ($geoobject) {
                    $condition = '(
                        6371 * acos(
                            cos(radians(' . $geoobject->lat . '))
                            * cos(radians(`lat`))
                            * cos(radians(`long`) - radians(' . $geoobject->long . '))
                            + sin(radians(' . $geoobject->lat . ')) * sin(radians(`lat`))
                        ) < 10
                    )';
                    $query->whereRaw($condition);
                }
                //TODO: Apartment Filter children & adults
            }
            )->when($request->adults && $request->children, callback: function ($query) use ($request): void {
                $query->withWhereHas(relation: 'apartments', callback: function ($query) use ($request): void {
                    $query->where('capacity_adults', '>=', $request->adults)
                        ->where('capacity_children', '>=', $request->children)
                        ->orderBy('capacity_adults')
                        ->orderBy('capacity_children')
                        //TODO: eloquent-eager-limit
                        ->take(1);
                })

                    //TODO: Filter By Facilities
                    ->when($request->facilities, function (Builder $query) use ($request) {
                        $query->whereHas('facilities', callback: function (Builder $query) use ($request) {
                            $query->whereIn('facilities.id', $request->facilities);
                        });
                    });
            })->get();

        //TODO : Filtering collection without any extra query
        //TODO: properties collection into a single dimension
        $allFacilities = $properties->pluck('facilities')->flatten();
        $facilities = $allFacilities->unique('name')
            ->mapWithKeys(function ($facility) use ($allFacilities) {
                /*
                 * return array
                 * facilities.name => properties.facilities.name
                 * */
                return [
                    $facility->name => $allFacilities->where('name', $facility->name)->count(),
                ];
            })
            ->sortDesc();

        //TODO: Alternative extra query
        /* $facilities = Facility::query()
             ->withCount(['properties' => function ($property) use ($properties) {
                 $property->whereIn('id', $properties->pluck('id'));
             }])
             ->get()
             ->where('properties_count', '>', 0)
             ->sortByDesc('properties_count')
             ->pluck('properties_count', 'name');
        */
        return [
            'properties' => PropertySearchResource::collection($properties),
            'facilities' => $facilities,
        ];
    }
}
