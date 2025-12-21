<?php

declare(strict_types=1);

namespace Sevada\NieValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Sevada\NieValidator\Rules\Nie;

/**
 * NIE Validator Service Provider
 *
 * Registers the NIE validation rule with Laravel's validator
 * and publishes configuration and translation files.
 */
class NieValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap package services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/nie.php' => config_path('nie.php'),
        ], 'nie-config');

        // Publish translation files
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/nie-validator'),
        ], 'nie-translations');

        // Load package translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'nie-validator');

        // Register the "nie" validation rule
        Validator::extend(
            'nie',
            fn ($attribute, $value) => (new Nie())->passes($attribute, $value),
            trans('nie-validator::validation.nie')
        );
    }

    /**
     * Register package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge default configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nie.php',
            'nie'
        );
    }
}