<?php

declare(strict_types=1);

namespace Sevada\NieValidator\Tests\Integration;

use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use Sevada\NieValidator\NieValidatorServiceProvider;

/**
 * Integration tests for NIE Validator Service Provider
 *
 * Tests the Laravel integration including:
 * - Service provider registration
 * - Validator facade integration
 * - Config and translation loading
 */
class ServiceProviderTest extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [NieValidatorServiceProvider::class];
    }

    public function test_nie_rule_registered_with_validator_facade(): void
    {
        $validator = Validator::make(
            ['nie' => 'X1234567L'],
            ['nie' => 'nie']
        );

        $this->assertTrue($validator->passes());
    }

    public function test_nie_rule_fails_with_invalid_nie(): void
    {
        $validator = Validator::make(
            ['nie' => 'INVALID'],
            ['nie' => 'nie']
        );

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('nie', $validator->errors()->toArray());
    }

    public function test_nie_rule_with_multiple_validation_rules(): void
    {
        $validator = Validator::make(
            ['nie' => 'X1234567L'],
            ['nie' => 'required|nie']
        );

        $this->assertTrue($validator->passes());
    }

    public function test_nie_rule_fails_when_required_and_empty(): void
    {
        $validator = Validator::make(
            ['nie' => ''],
            ['nie' => 'required|nie']
        );

        $this->assertFalse($validator->passes());
    }

    public function test_config_is_loaded(): void
    {
        $controlLetters = config('nie.control_letters');
        
        $this->assertIsArray($controlLetters);
        $this->assertCount(23, $controlLetters);
        $this->assertEquals('T', $controlLetters[0]);
    }

    public function test_translations_are_loaded(): void
    {
        $message = trans('nie-validator::validation.nie');
        
        $this->assertIsString($message);
        $this->assertNotEmpty($message);
        $this->assertStringContainsString('NIE', $message);
    }

    public function test_validation_error_message_is_translatable(): void
    {
        $validator = Validator::make(
            ['document' => 'INVALID'],
            ['document' => 'nie']
        );

        $this->assertFalse($validator->passes());
        
        $errors = $validator->errors()->get('document');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('NIE', $errors[0]);
    }

    /**
     * @dataProvider validNieProvider
     */
    public function test_validator_facade_with_valid_nies(string $nie): void
    {
        $validator = Validator::make(
            ['nie' => $nie],
            ['nie' => 'nie']
        );

        $this->assertTrue($validator->passes(), "NIE '{$nie}' should pass validation");
    }

    /**
     * Valid NIE test cases for integration testing
     *
     * @return array<string, array<string>>
     */
    public static function validNieProvider(): array
    {
        return [
            'X prefix' => ['X1234567L'],
            'Y prefix' => ['Y1234567X'],
            'Z prefix' => ['Z1234567R'],
            'lowercase' => ['x1234567l'],
            'with spaces' => ['X 1234567 L'],
            'with hyphens' => ['X-1234567-L'],
        ];
    }
}