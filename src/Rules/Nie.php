<?php

namespace Sevada\NieValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Nie implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        $val = strtoupper(str_replace([' ', '-'], '', $value));

        // Format: X|Y|Z + 7 digits + letter
        if (! preg_match('/^[XYZ]\d{7}[A-Z]$/', $val)) {
            return false;
        }

        // Map prefix to digit
        $map = ['X' => '0', 'Y' => '1', 'Z' => '2'];
        $number = $map[$val[0]] . substr($val, 1, 7);
        $index  = intval($number) % 23;

        $letters  = config('nie.control_letters');
        $expected = $letters[$index] ?? null;

        return $val[8] === $expected;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute is not a valid NIE.';
    }
}