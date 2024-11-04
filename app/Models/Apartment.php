<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static \Database\Factories\ApartmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Apartment query()
 *
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
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(related: Property::class, foreignKey: 'property_id');
    }

    public function apartment_type(): BelongsTo
    {
        return $this->belongsTo(related: ApartmentType::class, foreignKey: 'apartment_type_id');
    }
}
