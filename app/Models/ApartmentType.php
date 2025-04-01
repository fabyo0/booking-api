<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ApartmentTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApartmentType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class ApartmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
