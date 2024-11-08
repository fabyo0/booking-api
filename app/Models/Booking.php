<?php

namespace App\Models;

use App\Observers\BookingObserver;
use App\Traits\ValidForRange;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read \App\Models\Apartment|null $apartment
 * @property-read \App\Models\User|null $user
 *
 * @method static \Database\Factories\BookingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking withoutTrashed()
 *
 * @property int $id
 * @property int $apartment_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int $guests_adults
 * @property int $guests_children
 * @property int $total_price
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $apartment_name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Booking validForRange(array $range = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereApartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereGuestsAdults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereGuestsChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ValidForRange;

    protected $fillable = [
        'apartment_id',
        'user_id',
        'start_date',
        'end_date',
        'guests_adults',
        'guests_children',
        'total_price',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $appends = ['apartment_name'];

    public function apartmentName(): Attribute
    {
        return new Attribute(
            get: fn (): string => $this->apartment->property->name.': '.$this->apartment->name
        );
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(related: Apartment::class, foreignKey: 'apartment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    }

    public static function booted(): void
    {
        parent::booted();

        self::observe(BookingObserver::class);
    }
}
