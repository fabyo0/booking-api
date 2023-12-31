<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lat',
        'long',
    ];

    public function city(): HasOne
    {
        return $this->hasOne(City::class);
    }
}
