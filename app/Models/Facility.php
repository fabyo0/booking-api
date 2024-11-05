<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 
 *
 * @property int $id
 * @property int|null $category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Apartment> $apartments
 * @property-read int|null $apartments_count
 * @property-read \App\Models\FacilityCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|Facility newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Facility newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Facility query()
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(related: FacilityCategory::class, foreignKey: 'category_id');
    }

    public function apartments(): BelongsToMany
    {
        return $this->belongsToMany(related: Apartment::class);
    }
}
