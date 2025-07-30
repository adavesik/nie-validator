# Laravel NIE Validation Package

This package provides a custom Laravel validation rule to verify Spanish NIE numbers (foreigners’ identification) according to the official algorithm regulated by **Real Decreto 1553/2005, Artículo 11**.

### Installation

```markdown
composer require sevada/nie-validator
```

### Usage

In your Form Request or validator rules:

```php
use Sevada\NieValidator\Rules\Nie;
$request->validate([
    'nie_number' => ['required', new Nie],
]);
```

Or with the nie shorthand:

```php
$request->validate([
    'nie_number' => ['required', 'nie'],
]);
```

### Testing

**Run:**

```shell
composer test
```
