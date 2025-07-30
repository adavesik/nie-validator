<?php

namespace Sevada\NieValidator\Tests\Integration;

use Orchestra\Testbench\TestCase;
use Sevada\NieValidator\NieValidatorServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [NieValidatorServiceProvider::class];
    }

    public function test_nie_rule_registered(): void
    {
        $validator = $this->app['validator']->make(
            ['nie' => 'X1234567L'],
            ['nie' => 'nie']
        );

        $this->assertTrue($validator->passes());
    }
}