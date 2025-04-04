<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $room_id
 * @property int $bed_type_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read BedType $bed_type
 * @property-read Room $room
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Bed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereBedTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bed whereUpdatedAt($value)
 * @method static \Database\Factories\BedFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
final class Bed extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'bed_type_id',
        'name',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(related: Room::class, foreignKey: 'room_id');
    }

    public function bed_type(): BelongsTo
    {
        return $this->belongsTo(related: BedType::class, foreignKey: 'bed_type_id');
    }
}
