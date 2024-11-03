<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\SearchRequest;
use App\Models\Property;
use Illuminate\Http\Request;
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
            ->when($request->country, function($query) use ($request) {
                $query->whereHas('city', fn($q) => $q->where('country_id', $request->country));
            })->get();
    }
}
