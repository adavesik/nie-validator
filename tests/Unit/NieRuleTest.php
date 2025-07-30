<?php

namespace Sevada\NieValidator\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Sevada\NieValidator\NieValidatorServiceProvider;
use Sevada\NieValidator\Rules\Nie;

class NieRuleTest extends TestCase
{
    /**
     * Register the package service provider so config() & Validator facade work.
     */
    protected function getPackageProviders($app): array
    {
        return [NieValidatorServiceProvider::class];
    }

    public function test_valid_nie(): void
    {
        $rule = new Nie();
        $this->assertTrue($rule->passes('nie', 'X1234567L'));
    }

    public function test_lowercase_nie(): void
    {
        $rule = new Nie();
        $this->assertTrue($rule->passes('nie', 'x1234567l'));
    }

    public function test_nie_with_spaces(): void
    {
        $rule = new Nie();
        $this->assertTrue($rule->passes('nie', 'X 1234567 L'));
    }

    public function test_nie_with_hyphens(): void
    {
        $rule = new Nie();
        $this->assertTrue($rule->passes('nie', 'X-1234567-L'));
    }

    public function test_zero_number_nie(): void
    {
        // 0000000 → index 0 → 'T'
        $rule = new Nie();
        $this->assertTrue($rule->passes('nie', 'X0000000T'));
    }

    public function test_invalid_prefix(): void
    {
        $rule = new Nie();
        $this->assertFalse($rule->passes('nie', 'A1234567L'));
    }

    public function test_invalid_nie_bad_letter(): void
    {
        $rule = new Nie();
        $this->assertFalse($rule->passes('nie', 'X1234567A'));
    }

    public function test_invalid_format(): void
    {
        $rule = new Nie();
        $this->assertFalse($rule->passes('nie', '12345678Z'));
    }
}