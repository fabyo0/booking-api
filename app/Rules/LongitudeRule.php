<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Override;

final class LongitudeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    #[Override]
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^[-]?((((1[0-7]\d)|(\d?\d))(\.(\d{1,8}))?)|180(\.0+)?)$/';

        if (in_array(preg_match($regex, (string) $value), [0, false], true)) {
            $fail('The :attribute must be a valid longitude coordinate in decimal degrees format.');
        }
    }
}
