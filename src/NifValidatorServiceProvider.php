<?php

namespace Sevada\NifValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Sevada\NifValidator\Rules\Nie;
use Sevada\NifValidator\Rules\Dni;

class NifValidatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/nif.php' => config_path('nif.php'),
        ], 'config');

        // Register the "nie" validation rule
        Validator::extend(
            'nie',
            fn($attribute, $value) => (new Nie())->passes($attribute, $value),
            'The :attribute is not a valid Spanish NIE.'
        );

        // Register the "dni" validation rule
        Validator::extend(
            'dni',
            fn($attribute, $value) => (new Dni())->passes($attribute, $value),
            'The :attribute is not a valid Spanish DNI.'
        );
    }

    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nif.php',
            'nif'
        );
    }
}