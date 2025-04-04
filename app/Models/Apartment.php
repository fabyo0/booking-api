<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * @method static \Database\Factories\ApartmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment query()
 *
 * @property int $id
 * @property int|null $apartment_type_id
 * @property int $property_id
 * @property string $name
 * @property int $capacity_adults
 * @property int $capacity_children
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $size
 * @property int $bathrooms
 * @property-read ApartmentType|null $apartment_type
 * @property-read Property $property
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereApartmentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereBathrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereCapacityAdults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereCapacityChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Room> $rooms
 * @property-read int|null $rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bed> $beds
 * @property-read int|null $beds_count
 * @property-read mixed $beds_list
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Facility> $facilities
 * @property-read int|null $facilities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ApartmentPrice> $prices
 * @property-read int|null $prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Booking> $booking
 * @property-read int|null $booking_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Booking> $bookings
 * @property-read int|null $bookings_count
 *
 * @mixin \Eloquent
 */
final class Apartment extends Model
{
    use HasEagerLimit;
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'capacity_adults',
        'capacity_children',
        'apartment_type_id',
        'size',
        'bathrooms',
    ];

    protected $appends = [
        'beds_list',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(related: Property::class, foreignKey: 'property_id');
    }

    public function apartment_type(): BelongsTo
    {
        return $this->belongsTo(related: ApartmentType::class, foreignKey: 'apartment_type_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(related: Room::class);
    }

    public function beds(): HasManyThrough
    {
        return $this->hasManyThrough(related: Bed::class, through: Room::class);
    }

    public function bedsList(): Attribute
    {
        return new Attribute(
            get: function (): string {

                if ( ! $this->relationLoaded('beds')) {
                    $this->load('beds.bed_type');
                }

                $allBeds = $this->beds()->get();
                $bedsByType = $allBeds->groupBy('bed_type.name');
                $bedsList = '';

                if (1 === $bedsByType->count()) {
                    $bedsList = $allBeds->count() . ' ' . str($bedsByType->keys()[0])->plural($allBeds->count());
                } elseif ($bedsByType->count() > 1) {
                    $bedsList = $allBeds->count() . ' ' . str('bed')->plural($allBeds->count());
                    $bedsListArray = [];
                    foreach ($bedsByType as $bedType => $beds) {
                        $bedsListArray[] = $beds->count() . ' ' . str($bedType)->plural($beds->count());
                    }
                    $bedsList .= ' (' . implode(', ', $bedsListArray) . ')';
                }

                return $bedsList;
            },
        );
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(related: Facility::class, table: 'apartment_facility');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(related: ApartmentPrice::class);
    }

    public function assignFacilities(array $facilityIds): array
    {
        return $this->facilities()->sync($facilityIds, false);
    }

    public function calculatePriceForDates($startDate, $endDate): int|float
    {
        if ( ! $startDate instanceof Carbon) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }

        if ( ! $endDate instanceof Carbon) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        $cost = 0;
        while ($startDate->lte($endDate)) {
            $cost += $this->prices->where(fn(ApartmentPrice $price): bool => $price->start_date->lte($startDate) && $price->end_date->gte($startDate))->value('price');

            $startDate->addDay();
        }

        return $cost;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(related: Booking::class);
    }
}
