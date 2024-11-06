<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $apartment_id
 * @property int|null $room_type_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Apartment $apartment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bed> $beds
 * @property-read int|null $beds_count
 * @property-read \App\Models\RoomType|null $room_type
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereApartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereRoomTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereUpdatedAt($value)
 * @method static \Database\Factories\RoomFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'room_type_id',
        'name',
    ];

    public function room_type(): BelongsTo
    {
        return $this->belongsTo(related: RoomType::class, foreignKey: 'room_type_id');
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(related: Apartment::class, foreignKey: 'apartment_id');
    }

    public function beds(): HasMany
    {
        return $this->hasMany(related: Bed::class);
    }
}
