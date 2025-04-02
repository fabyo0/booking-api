<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\PropertyObserver;
use Database\Factories\PropertyFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

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
 * @property-read Collection<int, Apartment> $apartments
 * @property-read int|null $apartments_count
 * @property-read mixed $address
 * @property-read Collection<int, Facility> $facilities
 * @property-read int|null $facilities_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, Booking> $bookings
 * @property-read int|null $bookings_count
 *
 * @mixin Eloquent
 */
final class Property extends Model implements HasMedia
{
    use HasEagerLimit;
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'owner_id',
        'name',
        'city_id',
        'address_street',
        'address_postcode',
        'lat',
        'long',
    ];

    protected $appends = ['address'];

    #[Override]
    public static function booted(): void
    {
        parent::booted();

        self::observe(PropertyObserver::class);
    }

    public function address(): Attribute
    {
        return new Attribute(
            get: fn(): string => $this->address_street
                . ', ' . $this->address_postcode
                . ', ' . $this->city->name,
        );
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(related: City::class, foreignKey: 'city_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'owner_id');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(related: Apartment::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(related: Facility::class, table: 'facility_property');
    }

    //Thumbnail Image
    #[Override]
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(800);
    }

    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(related: Booking::class, through: Apartment::class);
    }
}
