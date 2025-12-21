<?php

declare(strict_types=1);

namespace Sevada\NifValidator\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Sevada\NifValidator\NifValidatorServiceProvider;
use Sevada\NifValidator\Rules\Dni;

/**
 * Unit tests for DNI validation rule
 */
class DniRuleTest extends TestCase
{
    /**
     * Register the package service provider.
     */
    protected function getPackageProviders($app): array
    {
        return [NifValidatorServiceProvider::class];
    }

    /**
     * @dataProvider validDniProvider
     */
    public function test_valid_dni_numbers(string $dni): void
    {
        $rule = new Dni();
        $this->assertTrue($rule->passes('dni', $dni), "DNI '{$dni}' should be valid");
    }

    /**
     * @dataProvider invalidDniProvider
     */
    public function test_invalid_dni_numbers(mixed $dni, string $reason): void
    {
        $rule = new Dni();
        $this->assertFalse($rule->passes('dni', $dni), "DNI '{$dni}' should be invalid: {$reason}");
    }

    public function test_error_message_is_translatable(): void
    {
        $rule = new Dni();
        $message = $rule->message();
        $this->assertIsString($message);
        $this->assertNotEmpty($message);
    }

    public static function validDniProvider(): array
    {
        return [
            'Standard DNI' => ['12345678Z'],
            'Lowercase' => ['12345678z'],
            'With spaces' => ['12345678 Z'],
            'With hyphens' => ['12345678-Z'],
            'Minimum value (zeros)' => ['00000000T'],
            'Maximum value (nines)' => ['99999999R'],
            'Standard middle value' => ['54362315K'],
        ];
    }

    public static function invalidDniProvider(): array
    {
        return [
            // Type errors
            'null value' => [null, 'null is not a string'],
            'integer value' => [12345678, 'integer is not a string'],
            
            // Format errors
            'empty string' => ['', 'empty'],
            'too short' => ['1234567Z', 'missing digit'],
            'too long' => ['123456789Z', 'extra digit'],
            'missing letter' => ['12345678', 'no letter'],
            'letter first (NIE)' => ['X1234567L', 'NIE format'],
            
            // Checksum errors
            'wrong letter' => ['12345678A', 'wrong checksum'],
            'zeros with wrong letter' => ['00000000Z', 'should be T'],
            
            // Special chars
            'with dots' => ['12.345.678-Z', 'dots not allowed by default'],
        ];
    }
}
