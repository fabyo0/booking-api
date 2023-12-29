<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{

    public function __construct()
    {
        $this->authorize('properties-manage');
    }

    public function index()
    {
        return response()->json([
            'success' => true
        ]);
    }
}
