<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    /**
     * Booking Index
     *
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('bookings-manage');

        return response()->json([
            'success' => true,
        ]);
    }
}
