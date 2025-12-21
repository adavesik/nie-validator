<?php

declare(strict_types=1);

namespace Sevada\NieValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * NIE (Número de Identidad de Extranjero) Validation Rule
 *
 * Validates Spanish foreigner identification numbers according to the official algorithm
 * regulated by Real Decreto 1553/2005, Artículo 11.
 *
 * NIE Format: [X|Y|Z] + 7 digits + control letter
 * Example: X1234567L
 *
 * Algorithm:
 * 1. Replace prefix letter with digit: X=0, Y=1, Z=2
 * 2. Calculate modulo 23 of the resulting 8-digit number
 * 3. Use the remainder as index into the control letter array
 * 4. Verify the control letter matches
 *
 * @see https://www.boe.es/buscar/doc.php?id=BOE-A-2005-16909
 */
class Nie implements Rule
{
    /**
     * Valid NIE format pattern: prefix (X/Y/Z) + 7 digits + control letter
     */
    private const PATTERN = '/^[XYZ]\d{7}[A-Z]$/';

    /**
     * Mapping of NIE prefix letters to their numeric equivalents
     */
    private const PREFIX_MAP = [
        'X' => '0',
        'Y' => '1',
        'Z' => '2',
    ];

    /**
     * Control letters for remainders 0-22 (modulo 23)
     * Official sequence defined by Spanish regulation
     */
    private const CONTROL_LETTERS = 'TRWAGMYFPDXBNJZSQVHLCKE';

    /**
     * Modulo divisor for control letter calculation
     */
    private const MODULO = 23;

    /**
     * Position of the control letter in the NIE string (0-indexed)
     */
    private const CONTROL_LETTER_POSITION = 8;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute The attribute name being validated
     * @param mixed $value The value to validate
     * @return bool True if the NIE is valid, false otherwise
     */
    public function passes($attribute, $value): bool
    {
        // Ensure we have a string value
        if (! is_string($value)) {
            return false;
        }

        // Normalize: uppercase and remove spaces/hyphens
        $normalized = strtoupper(str_replace([' ', '-'], '', $value));

        // Validate format: X|Y|Z + 7 digits + letter
        if (! preg_match(self::PATTERN, $normalized)) {
            return false;
        }

        // Extract components
        $prefix = $normalized[0];
        $digits = substr($normalized, 1, 7);
        $providedLetter = $normalized[self::CONTROL_LETTER_POSITION];

        // Convert prefix to digit and build 8-digit number
        $numericValue = self::PREFIX_MAP[$prefix] . $digits;

        // Calculate expected control letter
        $expectedLetter = $this->calculateControlLetter((int) $numericValue);

        // Verify control letter matches
        return $providedLetter === $expectedLetter;
    }

    /**
     * Calculate the control letter for a given numeric value.
     *
     * @param int $numericValue The 8-digit numeric value (prefix digit + 7 digits)
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
        return trans('nie-validator::validation.nie');
    }
}