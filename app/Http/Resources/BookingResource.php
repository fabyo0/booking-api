<?php

namespace App\Http\Resources;

use App\Models\Apartment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property int $booking_id
 * @property int $guests_adults
 * @property int $guests_children
 * @property int $total_price
 * @property int $rating
 * @property string $review_comment
 * @property User $user
 * @property Apartment $apartment_name
 * @property ?Carbon $start_date
 * @property ?Carbon $end_date
 * @property ?Carbon $deleted_at
 */
class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apartment_name' => $this->apartment_name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'guests_adults' => $this->guests_adults,
            'guests_children' => $this->guests_children,
            'total_price' => $this->total_price,
            'cancelled_at' => $this->deleted_at?->toDateString(),
            'rating' => $this->rating,
            'review_comment' => $this->review_comment,
        ];
    }
}
