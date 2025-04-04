<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ApartmentType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * Transform the resource into an array.
 *
 * @property string $name
 * @property int $size
 * @property int $beds_list
 * @property int $bathrooms
 * @property array $facility_categories
 * @property ApartmentType|null $apartment_type
 */
final class ApartmentDetailsResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'type' => $this->apartment_type?->name,
            'size' => $this->size,
            'bed_lists' => $this->beds_list,
            'bathroom' => $this->bathrooms,
            'facility_categories' => $this->facility_categories,
        ];
    }
}
