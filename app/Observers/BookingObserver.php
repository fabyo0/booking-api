<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
{
    /**
     * Handle the Booking "creating" event.
     */
    public function creating(Booking $booking): void
    {
        $booking->total_price = $booking->apartment->calculatePriceForDates(
            startDate: $booking->start_date,
            endDate: $booking->end_date
        );
    }
}
