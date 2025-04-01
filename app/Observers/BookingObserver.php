<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Booking;

final class BookingObserver
{
    /**
     * Handle the Booking "creating" event.
     */
    public function creating(Booking $booking): void
    {
        $booking->total_price = $booking->apartment->calculatePriceForDates(
            startDate: $booking->start_date,
            endDate: $booking->end_date,
        );
    }
}
