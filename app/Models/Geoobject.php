<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $city_id
 * @property string $name
 * @property string|null $lat
 * @property string|null $long
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read City|null $city
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject query()
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Geoobject whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class Geoobject extends Model
{
    protected $fillable = [
        'city_id',
        'name',
        'lat',
        'long',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(related: City::class, foreignKey: 'city_id');
    }
}
