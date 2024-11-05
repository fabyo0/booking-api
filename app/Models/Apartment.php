<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * 
 *
 * @method static \Database\Factories\ApartmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment query()
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
 * @property-read \App\Models\ApartmentType|null $apartment_type
 * @property-read \App\Models\Property $property
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms
 * @property-read int|null $rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bed> $beds
 * @property-read int|null $beds_count
 * @property-read mixed $beds_list
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Facility> $facilities
 * @property-read int|null $facilities_count
 * @mixin \Eloquent
 */
class Apartment extends Model
{
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
        $allBeds = $this->beds;
        $bedsByType = $allBeds->groupBy('bed_type.name');
        $bedsList = '';
        if ($bedsByType->count() == 1) {
            $bedsList = $allBeds->count().' '.str($bedsByType->keys()[0])->plural($allBeds->count());
        } elseif ($bedsByType->count() > 1) {
            $bedsList = $allBeds->count().' '.str('bed')->plural($allBeds->count());
            $bedsListArray = [];
            foreach ($bedsByType as $bedType => $beds) {
                $bedsListArray[] = $beds->count().' '.str($bedType)->plural($beds->count());
            }
            $bedsList .= ' ('.implode(', ', $bedsListArray).')';
        }

        return new Attribute(
            get: fn (): string => $bedsList
        );
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(related: Facility::class, table: 'apartment_facility');
    }
}
