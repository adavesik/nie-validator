# Contributing to NIE Validator

Thank you for considering contributing to the NIE Validator package! This document outlines the process for contributing to this project.

## Code of Conduct

This project adheres to a code of conduct that all contributors are expected to follow. Please be respectful and constructive in all interactions.

## Development Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Git

### Getting Started

1. Fork the repository on GitHub
2. Clone your fork locally:
   ```bash
   git clone https://github.com/YOUR-USERNAME/nie-validator.git
   cd nie-validator
   ```

3. Install dependencies:
   ```bash
   composer install
   ```

4. Create a new branch for your feature or fix:
   ```bash
   git checkout -b feature/your-feature-name
   ```

## Development Workflow

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test:coverage
```

### Code Quality

Before submitting a pull request, ensure your code passes all quality checks:

```bash
# Run static analysis
composer analyze

# Check code style
composer format:check

# Fix code style automatically
composer format

# Run all checks
composer check
```

### Code Style

This project follows PSR-12 coding standards with Laravel-specific conventions. Use Laravel Pint to automatically format your code:

```bash
composer format
```

### Static Analysis

All code must pass PHPStan at level 8:

```bash
composer analyze
```

## Pull Request Process

1. **Update tests**: Add or update tests for your changes
2. **Run quality checks**: Ensure all tests pass and code quality checks succeed
3. **Update documentation**: Update README.md if you're adding new features
4. **Update CHANGELOG**: Add your changes to the "Unreleased" section
5. **Commit your changes**: Use clear, descriptive commit messages
6. **Push to your fork**: Push your branch to your GitHub fork
7. **Create a Pull Request**: Open a PR against the `main` branch

### Pull Request Guidelines

- **One feature per PR**: Keep pull requests focused on a single feature or fix
- **Clear description**: Explain what your PR does and why
- **Reference issues**: Link to any related issues
- **Tests required**: All new features must include tests
- **Documentation**: Update docs for user-facing changes

## Commit Message Guidelines

Use clear, descriptive commit messages:

```
Add support for custom error messages

- Added configuration option for custom messages
- Updated service provider to use config
- Added tests for custom message functionality
```

## Testing Guidelines

### Writing Tests

- Place unit tests in `tests/Unit/`
- Place integration tests in `tests/Integration/`
- Use data providers for testing multiple scenarios
- Include edge cases and error conditions
- Aim for 100% code coverage on new code

### Test Naming

Use descriptive test method names:

```php
public function test_valid_nie_with_x_prefix(): void
public function test_invalid_nie_with_wrong_checksum(): void
```

## Reporting Bugs

When reporting bugs, please include:

- PHP version
- Laravel version
- Steps to reproduce
- Expected behavior
- Actual behavior
- Any error messages or stack traces

## Suggesting Features

Feature suggestions are welcome! Please:

- Check if the feature has already been requested
- Clearly describe the use case
- Explain how it would benefit users
- Consider backward compatibility

## Questions?

If you have questions about contributing, feel free to open an issue for discussion.

## License

By contributing to this project, you agree that your contributions will be licensed under the MIT License.
