<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\PropertyObserver;
use Database\Factories\PropertyFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read City $city
 * @property-read User $owner
 *
 * @method static Builder|Property newModelQuery()
 * @method static Builder|Property newQuery()
 * @method static Builder|Property query()
 * @method static Builder|Property whereAddressPostcode($value)
 * @method static Builder|Property whereAddressStreet($value)
 * @method static Builder|Property whereCityId($value)
 * @method static Builder|Property whereCreatedAt($value)
 * @method static Builder|Property whereId($value)
 * @method static Builder|Property whereLat($value)
 * @method static Builder|Property whereLong($value)
 * @method static Builder|Property whereName($value)
 * @method static Builder|Property whereOwnerId($value)
 * @method static Builder|Property whereUpdatedAt($value)
 * @method static PropertyFactory factory($count = null, $state = [])
 *
 * @mixin Eloquent
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

    public function apartments(): HasMany
    {
        return $this->hasMany(related: Apartment::class);
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
