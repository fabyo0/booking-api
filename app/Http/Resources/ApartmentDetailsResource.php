<?php

namespace App\Http\Resources;

use App\Models\ApartmentType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transform the resource into an array.
 *
 * @property string $name
 * @property int $size
 * @property int $beds_list
 * @property int $bathroom
 * @property array $facility_categories
 * @property ApartmentType|null $apartment_type
 */
class ApartmentDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'type' => $this->apartment_type?->name,
            'size' => $this->size,
            'bed_lists' => $this->beds_list,
            'bathroom' => $this->bathroom,
            'facility_categories' => $this->facility_categories,
        ];
    }
}
