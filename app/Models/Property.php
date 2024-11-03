<?php

namespace App\Models;

use App\Observers\PropertyObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property int $city_id
 * @property string $address_street
 * @property string|null $address_postcode
 * @property string|null $lat
 * @property string|null $long
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\City $city
 * @property-read \App\Models\User $owner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereAddressPostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereAddressStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'city_id',
        'address_street',
        'address_postcode',
        'lat',
        'long',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(related: City::class, foreignKey: 'city_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public static function booted(): void
    {
        parent::booted();

        self::observe(PropertyObserver::class);

        // Model booting auto assign owner_id
      /*  static::creating(function (self $property) {
            $property->owner()->associate(Auth::user());
        });*/
    }
}
