<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $invoice_address
 * @property string|null $invoice_postcode
 * @property string|null $invoice_city
 * @property int|null $invoice_country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read UserProfile $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereInvoiceAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereInvoiceCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereInvoiceCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereInvoicePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class UserProfile extends Model
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
