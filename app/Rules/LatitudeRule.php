<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LatitudeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^[-]?((([0-8]?\d)(\.(\d{1,8}))?)|(90(\.0+)?))$/';

        if (in_array(preg_match($regex, $value), [0, false], true)) {
            $fail('The :attribute must be a valid latitude coordinate in decimal degrees format.');
        }
    }
}
