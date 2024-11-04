<?php

declare(strict_types=1);

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Models\Property;
use Illuminate\Support\Facades\Gate;

class PropertyController extends Controller
{
    public function index()
    {
        Gate::authorize('properties-manage');

        return response()->json([
            'success' => true,
        ]);
    }

    public function store(StorePropertyRequest $request)
    {
        Gate::authorize('properties-manage');

        return Property::create($request->validated());
    }
}
