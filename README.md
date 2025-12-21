# Laravel NIE Validation Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sevada/nie-validator.svg?style=flat-square)](https://packagist.org/packages/sevada/nie-validator)
[![Total Downloads](https://img.shields.io/packagist/dt/sevada/nie-validator.svg?style=flat-square)](https://packagist.org/packages/sevada/nie-validator)
[![License](https://img.shields.io/packagist/l/sevada/nie-validator.svg?style=flat-square)](https://packagist.org/packages/sevada/nie-validator)

A robust Laravel validation package for Spanish NIE (Número de Identidad de Extranjero) numbers. Validates NIE numbers according to the official algorithm regulated by **Real Decreto 1553/2005, Artículo 11**.

## Features

✅ **Accurate Validation** - Implements the official Spanish NIE algorithm  
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
php artisan vendor:publish --tag=nie-config
```

Publish translation files:

```bash
php artisan vendor:publish --tag=nie-translations
```

## Usage

### Basic Validation

Use the `nie` validation rule in your validation rules:

```php
use Illuminate\Http\Request;

public function store(Request $request)
{
    $validated = $request->validate([
        'nie_number' => ['required', 'nie'],
    ]);
    
    // NIE is valid!
}
```

### Using the Rule Class

You can also use the `Nie` rule class directly:

```php
use Sevada\NieValidator\Rules\Nie;

$request->validate([
    'nie_number' => ['required', new Nie],
]);
```

### Form Request Validation

```php
use Illuminate\Foundation\Http\FormRequest;
use Sevada\NieValidator\Rules\Nie;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nie' => ['required', new Nie],
            'email' => ['required', 'email'],
        ];
    }
}
```

### Accepted Formats

The validator automatically normalizes input and accepts various formats:

```php
'X1234567L'      // ✅ Standard format
'x1234567l'      // ✅ Lowercase
'X 1234567 L'    // ✅ With spaces
'X-1234567-L'    // ✅ With hyphens
'x-1234567 L'    // ✅ Mixed formatting
```

### Valid NIE Prefixes

The validator supports all three official NIE prefixes:

- **X** - Original NIE prefix (mapped to 0)
- **Y** - Second series (mapped to 1)
- **Z** - Third series (mapped to 2)

## How It Works

The NIE validation algorithm:

1. **Normalize Input** - Convert to uppercase, remove spaces and hyphens
2. **Validate Format** - Check pattern: `[X|Y|Z] + 7 digits + letter`
3. **Convert Prefix** - Replace X→0, Y→1, Z→2
4. **Calculate Checksum** - Compute `number % 23`
5. **Verify Control Letter** - Compare with official letter sequence

### Control Letter Sequence

The official control letter sequence (indices 0-22):

```
T R W A G M Y F P D X B N J Z S Q V H L C K E
```

## Configuration

The default configuration (`config/nie.php`):

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
```

### Spanish

```
El :attribute no es un NIE (Número de Identidad de Extranjero) válido.
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

Run tests with coverage:

```bash
composer test:coverage
```

Run static analysis:

```bash
composer analyze
```

Check code style:

```bash
composer format:check
```

Run all quality checks:

```bash
composer check
```

## Examples

### Valid NIE Numbers

```php
X0000000T  // Minimum value
X1234567L  // Standard example
Y1234567X  // Y prefix
Z9999999V  // Maximum value
```

### Invalid NIE Numbers

```php
A1234567L  // ❌ Invalid prefix (must be X, Y, or Z)
X1234567A  // ❌ Wrong control letter
12345678Z  // ❌ Missing prefix (this is DNI format)
X123456L   // ❌ Too few digits
```

## Security

This package validates NIE numbers but does not verify their authenticity with official databases. For security considerations and best practices, see [SECURITY.md](SECURITY.md).

**Important**: NIE numbers are personal data under GDPR. Handle them responsibly:

- Only collect when necessary
- Encrypt at rest
- Transmit over HTTPS only
- Implement proper access controls
- Maintain audit logs

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for recent changes.

## Credits

- [Sevada](https://github.com/sevada)
- Algorithm based on Real Decreto 1553/2005, Artículo 11

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

## Support

- **Issues**: [GitHub Issues](https://github.com/sevada/nie-validator/issues)
- **Documentation**: [README.md](README.md)
- **Security**: [SECURITY.md](SECURITY.md)

---

Made with ❤️ for the Laravel community
