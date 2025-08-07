# Laravel NIF (NIE/DNI) Validation Package

This package provides custom Laravel validation rules to verify Spanish NIF numbers — covering both NIE (foreigners' identification) and DNI — according to the official algorithm regulated by **Real Decreto 1553/2005, Artículo 11**.

### Installation

```markdown
composer require sevada/nif-validator
```

### Usage

In your Form Request or validator rules:

```php
use Sevada\NifValidator\Rules\Nie;
use Sevada\NifValidator\Rules\Dni;
$request->validate([
    'nie_number' => ['required', new Nie],
    'dni_number' => ['required', new Dni],
]);
```

Or with the `nie`/`dni` shorthands:

```php
$request->validate([
    'nie_number' => ['required', 'nie'],
    'dni_number' => ['required', 'dni'],
]);
```

### Testing

**Run:**

```shell
composer test
```
