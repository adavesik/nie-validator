<?php

declare(strict_types=1);

namespace Sevada\NifValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Sevada\NifValidator\Rules\Dni;
use Sevada\NifValidator\Rules\Nie;

/**
 * NIF (NIE/DNI) Validator Service Provider
 *
 * Registers the NIE and DNI validation rules with Laravel's validator
 * and publishes configuration and translation files.
 */
class NifValidatorServiceProvider extends ServiceProvider
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
            __DIR__ . '/../config/nif.php' => config_path('nif.php'),
        ], 'nif-config');

        // Publish translation files
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/nif-validator'),
        ], 'nif-translations');

        // Load package translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'nif-validator');

        // Register the "nie" validation rule
        Validator::extend(
            'nie',
            fn ($attribute, $value) => (new Nie())->passes($attribute, $value),
            trans('nif-validator::validation.nie')
        );

        // Register the "dni" validation rule
        Validator::extend(
            'dni',
            fn ($attribute, $value) => (new Dni())->passes($attribute, $value),
            trans('nif-validator::validation.dni')
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
            __DIR__ . '/../config/nif.php',
            'nif'
        );
    }
}