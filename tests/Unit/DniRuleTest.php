<?php

namespace Sevada\NifValidator\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Sevada\NifValidator\NifValidatorServiceProvider;
use Sevada\NifValidator\Rules\Dni;

class DniRuleTest extends TestCase
{
    /**
     * Register the package service provider so config() & Validator facade work.
     */
    protected function getPackageProviders($app): array
    {
        return [NifValidatorServiceProvider::class];
    }

    public function test_valid_dni(): void
    {
        $rule = new Dni();
        $this->assertTrue($rule->passes('dni', '12345678Z'));
    }

    public function test_lowercase_dni(): void
    {
        $rule = new Dni();
        $this->assertTrue($rule->passes('dni', '12345678z'));
    }

    public function test_dni_with_spaces(): void
    {
        $rule = new Dni();
        $this->assertTrue($rule->passes('dni', '12345678 Z'));
    }

    public function test_dni_with_hyphens(): void
    {
        $rule = new Dni();
        $this->assertTrue($rule->passes('dni', '12345678-Z'));
    }

    public function test_zero_number_dni(): void
    {
        // 00000000 → index 0 → 'T'
        $rule = new Dni();
        $this->assertTrue($rule->passes('dni', '00000000T'));
    }

    public function test_invalid_dni_bad_letter(): void
    {
        $rule = new Dni();
        $this->assertFalse($rule->passes('dni', '12345678A'));
    }

    public function test_invalid_format(): void
    {
        $rule = new Dni();
        $this->assertFalse($rule->passes('dni', 'X1234567L'));
    }
}
