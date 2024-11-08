<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BedType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BedType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BedType query()
 * @method static \Illuminate\Database\Eloquent\Builder|BedType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BedType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BedType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BedType whereUpdatedAt($value)
 * @method static \Database\Factories\BedTypeFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class BedType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
