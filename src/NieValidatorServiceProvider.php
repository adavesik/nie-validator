<?php

namespace Sevada\NieValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Sevada\NieValidator\Rules\Nie;

class NieValidatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/nie.php' => config_path('nie.php'),
        ], 'config');

        // Register the "nie" validation rule
        Validator::extend(
            'nie',
            fn($attribute, $value) => (new Nie())->passes($attribute, $value),
            'The :attribute is not a valid Spanish NIE.'
        );
    }

    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nie.php',
            'nie'
        );
    }
}