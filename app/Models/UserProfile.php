<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_address',
        'invoice_postcode',
        'invoice_city',
        'invoice_country_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }
}
