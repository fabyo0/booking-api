<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
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
    public function store(StoreBookingRequest $request): \App\Http\Resources\BookingResource
    {
        $this->authorize('bookings-manage');

        $booking = auth()->user()->bookings()->create($request->validated());

        return new BookingResource($booking);
    }

    /**
     * Booking Show
     */
    public function show(Booking $booking)
    {
        $this->authorize('bookings-manage');

        abort_if($booking->user_id != auth()->id(), Response::HTTP_FORBIDDEN);

        return new BookingResource($booking);
    }

    /**
     * Booking Destroy
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('bookings-manage');

        abort_if($booking->user_id != auth()->id(), Response::HTTP_FORBIDDEN);

        $booking->delete();

        return response()->noContent();
    }
}
