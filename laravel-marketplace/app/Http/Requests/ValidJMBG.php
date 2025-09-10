<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;

class ValidJMBG implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) !== 13 || !ctype_digit($value)) {
            return false;
        }

        // JMBG checksum validation
        $weights = [7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        
        for ($i = 0; $i < 12; $i++) {
            $sum += intval($value[$i]) * $weights[$i];
        }
        
        $remainder = $sum % 11;
        $checkDigit = $remainder < 2 ? $remainder : 11 - $remainder;
        
        return $checkDigit == intval($value[12]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid Serbian JMBG with correct checksum.';
    }
}