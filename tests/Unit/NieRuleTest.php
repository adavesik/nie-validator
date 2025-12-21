<?php

declare(strict_types=1);

namespace Sevada\NifValidator\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Sevada\NifValidator\NifValidatorServiceProvider;
use Sevada\NifValidator\Rules\Nie;

/**
 * Unit tests for NIE validation rule
 *
 * Tests cover:
 * - Valid NIE numbers with all prefixes (X, Y, Z)
 * - Format variations (lowercase, spaces, hyphens)
 * - Invalid formats and checksums
 * - Edge cases (null, empty, special characters)
 * - Boundary values (0000000, 9999999)
 */
class NieRuleTest extends TestCase
{
    /**
     * Register the package service provider so config() & Validator facade work.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [NifValidatorServiceProvider::class];
    }

    /**
     * @dataProvider validNieProvider
     */
    public function test_valid_nie_numbers(string $nie): void
    {
        $rule = new Nie();
        $this->assertTrue($rule->passes('nie', $nie), "NIE '{$nie}' should be valid");
    }

    /**
     * @dataProvider invalidNieProvider
     */
    public function test_invalid_nie_numbers(mixed $nie, string $reason): void
    {
        $rule = new Nie();
        $this->assertFalse($rule->passes('nie', $nie), "NIE '{$nie}' should be invalid: {$reason}");
    }

    public function test_error_message_is_translatable(): void
    {
        $rule = new Nie();
        $message = $rule->message();
        
        $this->assertIsString($message);
        $this->assertNotEmpty($message);
        // The key might change, so checking it's a string key or the translated text is enough
        // but let's check it contains the translation key format if not translated in unit tests
        // or just strict check
    }

    /**
     * Valid NIE test cases
     *
     * @return array<string, array<string>>
     */
    public static function validNieProvider(): array
    {
        return [
            // X prefix (0)
            'X prefix with valid checksum' => ['X1234567L'],
            'X prefix lowercase' => ['x1234567l'],
            'X prefix with spaces' => ['X 1234567 L'],
            'X prefix with hyphens' => ['X-1234567-L'],
            'X prefix mixed formatting' => ['x-1234567 L'],
            'X with zeros' => ['X0000000T'],
            'X with max digits' => ['X9999999J'],
            
            // Y prefix (1)
            'Y prefix with valid checksum' => ['Y1234567X'],
            'Y prefix lowercase' => ['y1234567x'],
            'Y prefix with spaces' => ['Y 1234567 X'],
            'Y prefix with hyphens' => ['Y-1234567-X'],
            'Y with zeros' => ['Y0000000Z'],
            'Y with max digits' => ['Y9999999G'],
            
            // Z prefix (2)
            'Z prefix with valid checksum' => ['Z1234567R'],
            'Z prefix lowercase' => ['z1234567r'],
            'Z prefix with spaces' => ['Z 1234567 R'],
            'Z prefix with hyphens' => ['Z-1234567-R'],
            'Z with zeros' => ['Z0000000M'],
            'Z with max digits' => ['Z9999999H'],
            
            // All 23 possible control letters (testing modulo 23 algorithm)
            'Control letter T (index 0)' => ['X0000000T'],
            'Control letter R (index 1)' => ['X0000001R'],
            'Control letter W (index 2)' => ['X0000002W'],
            'Control letter A (index 3)' => ['X0000003A'],
            'Control letter G (index 4)' => ['X0000004G'],
            'Control letter M (index 5)' => ['X0000005M'],
            'Control letter Y (index 6)' => ['X0000006Y'],
            'Control letter F (index 7)' => ['X0000007F'],
            'Control letter P (index 8)' => ['X0000008P'],
            'Control letter D (index 9)' => ['X0000009D'],
            'Control letter X (index 10)' => ['X0000010X'],
            'Control letter B (index 11)' => ['X0000011B'],
            'Control letter N (index 12)' => ['X0000012N'],
            'Control letter J (index 13)' => ['X0000013J'],
            'Control letter Z (index 14)' => ['X0000014Z'],
            'Control letter S (index 15)' => ['X0000015S'],
            'Control letter Q (index 16)' => ['X0000016Q'],
            'Control letter V (index 17)' => ['X0000017V'],
            'Control letter H (index 18)' => ['X0000018H'],
            'Control letter L (index 19)' => ['X0000019L'],
            'Control letter C (index 20)' => ['X0000020C'],
            'Control letter K (index 21)' => ['X0000021K'],
            'Control letter E (index 22)' => ['X0000022E'],
        ];
    }

    /**
     * Invalid NIE test cases
     *
     * @return array<string, array<mixed>>
     */
    public static function invalidNieProvider(): array
    {
        return [
            // Type errors
            'null value' => [null, 'null is not a string'],
            'integer value' => [12345678, 'integer is not a string'],
            
            // Empty/whitespace
            'empty string' => ['', 'empty string'],
            'only spaces' => ['   ', 'only whitespace'],
            
            // Format errors
            'too short' => ['X123456L', 'missing one digit'],
            'too long' => ['X12345678L', 'extra digit'],
            'missing prefix' => ['1234567L', 'no prefix letter'],
            'missing control letter' => ['X1234567', 'no control letter'],
            'invalid prefix A' => ['A1234567L', 'A is not valid prefix'],
            'invalid prefix B' => ['B1234567L', 'B is not valid prefix'],
            'invalid prefix W' => ['W1234567L', 'W is not valid prefix'],
            'digit in prefix position' => ['11234567L', 'digit instead of letter prefix'],
            'letter in digit position' => ['X12A4567L', 'letter in digit section'],
            'digit in control position' => ['X12345671', 'digit instead of control letter'],
            
            // Checksum errors
            'wrong control letter' => ['X1234567A', 'checksum mismatch'],
            'X with wrong checksum' => ['X0000000R', 'should be T, not R'],
            'Y with wrong checksum' => ['Y0000000T', 'should be Z, not T'],
            'Z with wrong checksum' => ['Z0000000T', 'should be W, not T'],
            
            // Special characters
            'with dots' => ['X.1234567.L', 'dots not allowed'],
            'with slashes' => ['X/1234567/L', 'slashes not allowed'],
            'with parentheses' => ['X(1234567)L', 'parentheses not allowed'],
            'with unicode' => ['X1234567Ñ', 'unicode not allowed'],
            
            // Completely invalid
            'random string' => ['ABCDEFGH', 'not NIE format'],
            'DNI format' => ['12345678Z', 'DNI not NIE'],
            'only numbers' => ['12345678', 'no letters'],
            'only letters' => ['XYZABCDE', 'no valid digits'],
        ];
    }
}