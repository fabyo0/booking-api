<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $description
 * @property float $lat
 * @property float $long
 * @property string|null $location
 * @property Property|null $property_type
 * @property Collection|Media[] $media
 * @property ApartmentSearchResource $apartments
 * @property Booking $bookings_avg_rating
 */
final class PropertySearchResource extends JsonResource
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
            'address' => $this->address,
            'lat' => $this->lat,
            'long' => $this->long,
            'apartments' => ApartmentSearchResource::collection($this->apartments),
            'media' => $this->media->map(fn($media) => $media->getUrl('thumbnail')),
            'avg_rating' => $this->bookings_avg_rating,
        ];
    }
}
