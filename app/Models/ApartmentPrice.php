<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\ValidForRange;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $apartment_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Apartment $apartment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice whereApartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentPrice whereUpdatedAt($value)
 * @method static Builder|ApartmentPrice validForRange(array $range = [])
 *
 * @mixin \Eloquent
 */
final class ApartmentPrice extends Model
{
    use HasFactory;
    use ValidForRange;

    protected $fillable = [
        'apartment_id',
        'start_date',
        'end_date',
        'price',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(related: Apartment::class, foreignKey: 'apartment_id');
    }
}
