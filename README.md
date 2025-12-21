# Laravel NIF (NIE/DNI) Validator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sevada/nie-validator.svg?style=flat-square)](https://packagist.org/packages/sevada/nie-validator)
[![Total Downloads](https://img.shields.io/packagist/dt/sevada/nie-validator.svg?style=flat-square)](https://packagist.org/packages/sevada/nie-validator)
[![License](https://img.shields.io/packagist/l/sevada/nie-validator.svg?style=flat-square)](https://packagist.org/packages/sevada/nie-validator)

A robust Laravel validation package for Spanish identification numbers:
- **NIE** (Número de Identidad de Extranjero)
- **DNI** (Documento Nacional de Identidad)

Validates according to the official algorithm regulated by **Real Decreto 1553/2005, Artículo 11**.

## Features

✅ **Combined Support** - Validates both NIE and DNI numbers  
✅ **Accurate Validation** - Implements official Spanish algorithms  
✅ **Flexible Input** - Accepts various formats (spaces, hyphens, mixed case)  
✅ **Laravel Integration** - Seamless integration with Laravel's validator  
✅ **Multilingual** - Built-in English and Spanish error messages  
✅ **Type Safe** - Strict type declarations throughout  
✅ **Well Tested** - 100+ test cases with comprehensive coverage  
✅ **Zero Dependencies** - No external dependencies beyond Laravel

## Requirements

- PHP 8.2 or higher
- Laravel 10.x, 11.x, or 12.x

## Installation

Install via Composer:

```bash
composer require sevada/nie-validator
```

The service provider will be automatically registered.

### Publishing Configuration (Optional)

Publish the configuration file:

```bash
php artisan vendor:publish --tag=nif-config
```

Publish translation files:

```bash
php artisan vendor:publish --tag=nif-translations
```

## Usage

### Basic Validation

Use the `nie` or `dni` validation rules in your validation logic:

```php
use Illuminate\Http\Request;

public function store(Request $request)
{
    $validated = $request->validate([
        'nie_number' => ['required', 'nie'],
        'dni_number' => ['required', 'dni'],
    ]);
    
    // Numbers are valid!
}
```

### Using Rule Classes

You can also use the rule classes directly:

```php
use Sevada\NifValidator\Rules\Nie;
use Sevada\NifValidator\Rules\Dni;

$request->validate([
    'nie_number' => ['required', new Nie],
    'dni_number' => ['required', new Dni],
]);
```

### Accepted Formats

The validator automatically normalizes input and accepts various formats:

**NIE Examples:**
```php
'X1234567L'      // ✅ Standard format
'Y-1234567-X'    // ✅ With hyphens
'Z 1234567 R'    // ✅ With spaces
```

**DNI Examples:**
```php
'12345678Z'      // ✅ Standard format
'12345678-Z'     // ✅ With hyphens
'12345678 Z'     // ✅ With spaces
```

## Configuration

The default configuration (`config/nif.php`):

```php
return [
    // Official control letter sequence (DO NOT MODIFY)
    'control_letters' => str_split('TRWAGMYFPDXBNJZSQVHLCKE'),
    
    // Validation strictness
    'strict_format' => false, // Allow spaces and hyphens
];
```

## Translations

### English (Default)

```
The :attribute is not a valid Spanish NIE (Número de Identidad de Extranjero).
The :attribute is not a valid Spanish DNI (Documento Nacional de Identidad).
```

### Spanish

```
El :attribute no es un NIE (Número de Identidad de Extranjero) válido.
El :attribute no es un DNI (Documento Nacional de Identidad) válido.
```

To use Spanish translations, set your application locale:

```php
app()->setLocale('es');
```

## Testing

Run the test suite:

```bash
composer test
```

## Security

This package validates mathematical format but does not verify authenticity with official databases. See [SECURITY.md](SECURITY.md).

**Important**: Handle national ID numbers as sensitive personal data (GDPR).

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
