<?php

declare(strict_types=1);

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Resources\PropertySearchResource;
use App\Models\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PropertyController extends Controller
{
    /**
     * Property Index
     *
     * @return JsonResponse
     */
    public function index()
    {
        Gate::authorize('properties-manage');

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Property Store
     *
     * @return Property|Model
     */
    public function store(StorePropertyRequest $request)
    {
        Gate::authorize('properties-manage');

        return Property::create($request->validated());
    }
}
