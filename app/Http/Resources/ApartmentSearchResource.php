<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method Apartment calculatePriceForDates(mixed $start_date, mixed $end_date)
 */
class ApartmentSearchResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->apartment_type?->name,
            'size' => $this->size,
            'beds_list' => $this->beds_list,
            'bathrooms' => $this->bathrooms,
            'facilities' => FacilityResource::collection($this->whenLoaded(relationship: 'facilities')),
            'price' => $this->calculatePriceForDates($request->start_date, $request->end_date)];
    }
}
