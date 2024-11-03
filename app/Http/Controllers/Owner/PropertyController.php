<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PropertyController extends Controller
{

    public function index()
    {

        $this->authorize('properties-manage');

        return response()->json([
            'success' => true
        ]);
    }
}
