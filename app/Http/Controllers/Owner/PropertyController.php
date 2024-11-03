<?php

declare(strict_types=1);

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Models\Property;

final class PropertyController extends Controller
{
    public function index()
    {
        $this->authorize('properties-manage');

        return response()->json([
            'success' => true,
        ]);
    }

    public function store(StorePropertyRequest $request)
    {
        $this->authorize('properties-manage');

        return Property::create($request->validated());
    }
}
