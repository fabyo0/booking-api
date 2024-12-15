<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Apartment;
use App\Models\ApartmentType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method Apartment calculatePriceForDates(mixed $start_date, mixed $end_date)
 *
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property int $price
 * @property int $size
 * @property int $bathrooms
 * @property mixed $beds_list
 * @property array $features
 * @property ApartmentType|null $apartment_type
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
