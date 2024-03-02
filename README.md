# A plugin for filament that provides OpenAPI endpoints

[![Latest Version on Packagist](https://img.shields.io/packagist/v/evocative/filament-openapi.svg?style=flat-square)](https://packagist.org/packages/evocative/filament-openapi)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/evocative/filament-openapi/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/evocative/filament-openapi/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/evocative/filament-openapi/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/evocative/filament-openapi/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/evocative/filament-openapi.svg?style=flat-square)](https://packagist.org/packages/evocative/filament-openapi)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require evocative/filament-openapi
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-openapi-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-openapi-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-openapi-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentOpenAPI = new Evocative\FilamentOpenAPI();
echo $filamentOpenAPI->echoPhrase('Hello, Evocative!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Luis Bizarro](https://github.com/WildEgo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
