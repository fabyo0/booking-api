<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'bathrooms'
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
}
