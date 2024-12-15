<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final class BookingController extends Controller
{
    /**
     * Booking Index
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $this->authorize('bookings-manage');

        $bookings = auth()->user()->bookings()
            ->with('apartment.property')
            ->withTrashed()
            ->orderBy('start_date')
            ->get();

        return BookingResource::collection($bookings);
    }

    /**
     * Booking Store
     */
    public function store(StoreBookingRequest $request): BookingResource
    {
        $booking = auth()->user()->bookings()->create($request->validated());

        return new BookingResource($booking);
    }

    /**
     * Booking Show
     */
    public function show(Booking $booking): BookingResource
    {
        $this->authorize(PermissionEnum::BOOKINGS_MANAGE->value);

        abort_if($booking->user_id != auth()->id(), Response::HTTP_FORBIDDEN);

        return new BookingResource($booking);
    }

    /**
     * Booking Update
     */
    public function update(UpdateBookingRequest $request, Booking $booking): \App\Http\Resources\BookingResource
    {
        abort_if($booking->user_id != auth()->id(), Response::HTTP_FORBIDDEN);

        $booking->update($request->validated());

        return new BookingResource($booking);
    }

    /**
     * Booking Destroy
     */
    public function destroy(Booking $booking)
    {
        $this->authorize(PermissionEnum::BOOKINGS_MANAGE->value);

        abort_if($booking->user_id != auth()->id(), Response::HTTP_FORBIDDEN);

        $booking->delete();

        return response()->noContent();
    }
}
