# Changelog

All notable changes to `nie-validator` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Comprehensive test coverage with 100+ test cases
- PHPStan static analysis at level 8
- Laravel Pint code style enforcement
- Translation support (English and Spanish)
- Detailed PHPDoc comments throughout codebase
- Configuration file with customization options
- Strict type declarations across all files

### Changed
- **BREAKING**: Minimum PHP version raised to 8.2
- Improved validation logic with better null safety
- Enhanced error messages with translation support
- Refactored code to use constants instead of magic values

### Fixed
- PHP version compatibility issues with dependencies
- Potential null reference errors in validation logic

## [1.0.0] - Initial Release

### Added
- NIE validation rule for Laravel
- Support for all three NIE prefixes (X, Y, Z)
- Automatic normalization of input (handles spaces and hyphens)
- Service provider for automatic Laravel integration
- Basic test suite
- README documentation
