<?php

namespace Sevada\NifValidator\Tests\Integration;

use Orchestra\Testbench\TestCase;
use Sevada\NifValidator\NifValidatorServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [NifValidatorServiceProvider::class];
    }

    public function test_nie_rule_registered(): void
    {
        $validator = $this->app['validator']->make(
            ['nie' => 'X1234567L'],
            ['nie' => 'nie']
        );

        $this->assertTrue($validator->passes());
    }

    public function test_dni_rule_registered(): void
    {
        $validator = $this->app['validator']->make(
            ['dni' => '12345678Z'],
            ['dni' => 'dni']
        );

        $this->assertTrue($validator->passes());
    }
}