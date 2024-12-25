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
     *
     * @response AnonymousResourceCollection<LengthAwarePaginator<PlaceResource>>
     * */
    public function __invoke(SearchRequest $request): array
    {
        $propertyQuery = Property::with([
            'city', 'apartments.apartment_type',
            'apartments.rooms.beds.bed_type',
            'facilities',
            'media' => fn ($query) => $query->orderBy('position'),
            'apartments.prices' => function ($query) use ($request): void {
                //TODO: start_date & end_date null ise yarından itibaren iki gün için rezervasyon yapabilir
                $query->validForRange([
                    $request->start_date ?? now()->addDay()->toDateString(),
                    $request->end_date ?? now()->addDays(2)->toDateString(),
                ]);
            },
        ])
            ->withAvg('bookings', 'rating')
            //TODO: Price Filter price_from/price_to
            ->when($request->input('price_from'), callback: function (Builder $query) use ($request): void {
                $query->whereHas(relation: 'apartments.prices', callback: function (Builder $query) use ($request): void {
                    $query->where('price', '>=', $request->input('price_from'));
                });
            })
            ->when($request->input('price_to'), callback: function (Builder $query) use ($request): void {
                $query->whereHas(relation: 'apartments.prices', callback: function (Builder $query) use ($request): void {
                    $query->where('price', '<=', $request->input('price_to'));
                });
            })

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
            }
            )->when($request->adults && $request->children, callback: function ($query) use ($request): void {
                $query->withWhereHas(relation: 'apartments', callback: function ($query) use ($request): void {
                    $query->where('capacity_adults', '>=', $request->adults)
                        ->where('capacity_children', '>=', $request->children)
                        ->orderBy('capacity_adults')
                        ->orderBy('capacity_children')
                        //TODO: eloquent-eager-limitBuilder
                        ->take(1);
                })

                    //TODO: Filter By Facilities
                    ->when($request->facilities, function (Builder $query) use ($request): void {
                        $query->whereHas('facilities', callback: function (Builder $query) use ($request): void {
                            $query->whereIn('facilities.id', $request->facilities);
                        });
                    });
            })
            ->orderBy('bookings_avg_rating', 'desc');

        // Append all the current request's
        $properties = $propertyQuery->paginate(10)->withQueryString();

        //TODO : Filtering collection without any extra query
        //TODO: properties collection into a single dimension

        //        $allFacilities = $properties->pluck('facilities')->flatten();
        //        $facilities = $allFacilities->unique('name')
        //            ->mapWithKeys(fn ($facility) =>
        //                /*
        //                 * return array
        //                 * facilities.name => properties.facilities.name
        //                 * */
        //                [
        //                    $facility->name => $allFacilities->where('name', $facility->name)->count(),
        //                ])
        //            ->sortDesc();

        //TODO: Alternative extra query
        $facilities = Facility::query()
            ->withCount(['properties' => function ($property) use ($properties): void {
                $property->whereIn('id', $properties->pluck('id'));
            }])
            ->get()
            ->where('properties_count', '>', 0)
            ->sortByDesc('properties_count')
            ->pluck('properties_count', 'name');

        return [
            'properties' => PropertySearchResource::collection($properties)
                ->response()
             //Force pagination data
                ->getData(true),
            'facilities' => $facilities,
        ];
    }
}
