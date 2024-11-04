<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\SearchRequest;
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
        return Property::with('city.country')
            ->when($request->city, function ($query) use ($request) {
                $query->where('city_id', $request->city);
            })
            ->when($request->country, function ($query) use ($request) {
                $query->whereHas('city', fn($q) => $q->where('country_id', $request->country));
            })
            //TODO: Properties within 10 km
            ->when($request->geoobject, function ($query) use ($request) {
                $geoobject = Geoobject::find($request->geoobject);
                if ($geoobject) {
                    $condition = "(
                        6371 * acos(
                            cos(radians(" . $geoobject->lat . "))
                            * cos(radians(`lat`))
                            * cos(radians(`long`) - radians(" . $geoobject->long . "))
                            + sin(radians(" . $geoobject->lat . ")) * sin(radians(`lat`))
                        ) < 10
                    )";
                    $query->whereRaw($condition);
                }
            })->get();
    }
}
