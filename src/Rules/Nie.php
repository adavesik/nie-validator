<?php

declare(strict_types=1);

namespace Sevada\NifValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * NIE (Número de Identidad de Extranjero) Validation Rule
 *
 * Validates Spanish NIE numbers according to the official algorithm
 * regulated by Real Decreto 1553/2005, Artículo 11.
 *
 * NIE Format: [X|Y|Z] + 7 digits + control letter
 * Example: X1234567L
 *
 * Algorithm:
 * 1. Replace prefix (X→0, Y→1, Z→2)
 * 2. Calculate modulo 23 of the resulting 8-digit number
 * 3. Use the remainder as index into the control letter array
 * 4. Verify the control letter matches
 */
class Nie implements Rule
{
    /**
     * Valid NIE format pattern
     */
    private const PATTERN = '/^[XYZ]\d{7}[A-Z]$/';

    /**
     * Prefix mapping to digits
     */
    private const PREFIX_MAP = ['X' => '0', 'Y' => '1', 'Z' => '2'];

    /**
     * Control letters for remainders 0-22 (modulo 23)
     */
    private const CONTROL_LETTERS = 'TRWAGMYFPDXBNJZSQVHLCKE';

    /**
     * Modulo divisor for control letter calculation
     */
    private const MODULO = 23;

    /**
     * Position of the control letter in the normalized string
     */
    private const CONTROL_LETTER_POSITION = 8;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute  The attribute name being validated
     * @param  mixed  $value  The value to validate
     * @return bool True if the NIE is valid, false otherwise
     */
    public function passes($attribute, $value): bool
    {
        // Ensure we have a string value
        if (! is_string($value)) {
            return false;
        }

        // Normalize: uppercase and remove spaces/hyphens
        $val = strtoupper(str_replace([' ', '-'], '', $value));

        // Format: X|Y|Z + 7 digits + letter
        if (! preg_match(self::PATTERN, $val)) {
            return false;
        }

        // Calculate expected control letter
        $expected = $this->calculateControlLetter($val);

        return $val[self::CONTROL_LETTER_POSITION] === $expected;
    }

    /**
     * Calculate the expected control letter for a given NIE
     *
     * @param  string  $normalizedValue  The normalized NIE (e.g., X1234567L)
     * @return string The expected control letter
     */
    private function calculateControlLetter(string $normalizedValue): string
    {
        // Map prefix to digit: X→0, Y→1, Z→2
        $prefix = $normalizedValue[0];
        $number = self::PREFIX_MAP[$prefix].substr($normalizedValue, 1, 7);

        $index = (int) $number % self::MODULO;

        return self::CONTROL_LETTERS[$index];
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        $message = trans('nif-validator::validation.nie');

        return is_string($message) ? $message : 'The :attribute is not a valid Spanish NIE.';
    }
}
