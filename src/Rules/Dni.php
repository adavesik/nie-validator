<?php

declare(strict_types=1);

namespace Sevada\NifValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * DNI (Documento Nacional de Identidad) Validation Rule
 *
 * Validates Spanish national identification numbers according to the official algorithm
 * regulated by Real Decreto 1553/2005, Artículo 11.
 *
 * DNI Format: 8 digits + control letter
 * Example: 12345678Z
 *
 * Algorithm:
 * 1. Calculate modulo 23 of the 8-digit number
 * 2. Use the remainder as index into the control letter array
 * 3. Verify the control letter matches
 */
class Dni implements Rule
{
    /**
     * Valid DNI format pattern: 8 digits + control letter
     */
    private const PATTERN = '/^\d{8}[A-Z]$/';

    /**
     * Control letters for remainders 0-22 (modulo 23)
     */
    private const CONTROL_LETTERS = 'TRWAGMYFPDXBNJZSQVHLCKE';

    /**
     * Modulo divisor for control letter calculation
     */
    private const MODULO = 23;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute  The attribute name being validated
     * @param  mixed  $value  The value to validate
     * @return bool True if the DNI is valid, false otherwise
     */
    public function passes($attribute, $value): bool
    {
        // Ensure we have a string value
        if (! is_string($value)) {
            return false;
        }

        // Normalize: uppercase and remove spaces/hyphens
        $normalized = strtoupper(str_replace([' ', '-'], '', $value));

        // Format: 8 digits + letter
        if (! preg_match(self::PATTERN, $normalized)) {
            return false;
        }

        $digits = substr($normalized, 0, 8);
        $providedLetter = $normalized[8];

        // Calculate expected control letter
        $expectedLetter = $this->calculateControlLetter((int) $digits);

        return $providedLetter === $expectedLetter;
    }

    /**
     * Calculate the control letter for a given numeric value.
     *
     * @param  int  $numericValue  The 8-digit numeric value
     * @return string The expected control letter
     */
    private function calculateControlLetter(int $numericValue): string
    {
        $index = $numericValue % self::MODULO;

        return self::CONTROL_LETTERS[$index];
    }

    /**
     * Get the validation error message.
     *
     * @return string The error message (translatable)
     */
    public function message(): string
    {
        $message = trans('nif-validator::validation.dni');

        return is_string($message) ? $message : 'The :attribute is not a valid Spanish DNI.';
    }
}
