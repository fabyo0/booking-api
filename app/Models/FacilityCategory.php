<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Facility> $facilities
 * @property-read int|null $facilities_count
 * @method static \Illuminate\Database\Eloquent\Builder|FacilityCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacilityCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacilityCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacilityCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacilityCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacilityCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacilityCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacilityCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function facilities(): HasMany
    {
        return $this->hasMany(related: Facility::class, foreignKey: 'category_id');
    }
}
