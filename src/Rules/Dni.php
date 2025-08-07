<?php

namespace Sevada\NifValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Dni implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        $val = strtoupper(str_replace([' ', '-'], '', $value));

        // Format: 8 digits + letter
        if (! preg_match('/^\d{8}[A-Z]$/', $val)) {
            return false;
        }

        $number = substr($val, 0, 8);
        $index  = intval($number) % 23;

        $letters  = config('nif.control_letters');
        $expected = $letters[$index] ?? null;

        return $val[8] === $expected;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute is not a valid DNI.';
    }
}
