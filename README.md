# This is Laravel Theme.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/qirolab/laravel-theme.svg?style=flat-square)](https://packagist.org/packages/qirolab/laravel-theme)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/qirolab/laravel-theme/run-tests?label=tests)](https://github.com/qirolab/laravel-theme/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/qirolab/laravel-theme.svg?style=flat-square)](https://packagist.org/packages/qirolab/laravel-theme)


## Installation

You can install the package via composer:

```bash
composer require qirolab/laravel-theme
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Qirolab\Theme\ThemeServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Qirolab\Theme\ThemeServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravel-theme = new Qirolab\Theme();
echo $laravel-theme->echoPhrase('Hello, Qirolab!');
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

- [Harish Kumar](https://github.com/hkp22)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
