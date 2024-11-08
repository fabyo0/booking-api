<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Resources\BookingResource;
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
            'message' => 'success',
        ]);
    }

    /**
     * Booking Store
     */
    public function store(StoreBookingRequest $request): \App\Http\Resources\BookingResource
    {
        $this->authorize('bookings-manage');

        $booking = auth()->user()->bookings()->create($request->validated());

        return new BookingResource($booking);
    }
}
