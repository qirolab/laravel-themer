# Multi theme support for Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/qirolab/laravel-themer.svg?style=flat-square)](https://packagist.org/packages/qirolab/laravel-themer)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/qirolab/laravel-themer/Tests?label=Tests)](https://github.com/qirolab/laravel-themer/actions?query=workflow%3ATests+branch%3Amaster)
[![Styling](https://github.com/qirolab/laravel-themer/workflows/Check%20&%20fix%20styling/badge.svg)](https://github.com/qirolab/laravel-themer/actions?query=workflow%3A%22Check+%26+fix+styling%22)
[![Psalm](https://github.com/qirolab/laravel-themer/workflows/Psalm/badge.svg)](https://github.com/qirolab/laravel-themer/actions?query=workflow%3APsalm)
[![Total Downloads](https://img.shields.io/packagist/dt/qirolab/laravel-themer.svg?style=flat-square)](https://packagist.org/packages/qirolab/laravel-themer)


This Laravel package adds multi-theme support to your application. It also provides a simple authentication scaffolding for a starting point for building a Laravel application. And it also has preset for  `Bootstrap`, `Tailwind`, `Vue`, and `React`. So, I believe it is a good alternative to the `laravel/ui` & `laravel/breeze` package.

## Features
- Any number of themes
- Fallback theme support (WordPress style); It allows creating a child theme to extend any theme
- Provides authentication scaffolding similar to `laravel/ui` & `laravel/breeze`
- Exports all auth controllers, tests, and other files similar to `laravel/breeze`
- Provides frontend presets for `Bootstrap`, `Tailwind`, `Vue 2`, `Vue 3` and `React`

If you don't want to use auth scaffolding of this package, instead you want to
use Laravel Fortify, no problem with that. You can use Laravel Themer with
Fortify.  Laravel Fortify only gives backend implementation authentication, it
does not provide views or frontend presets. So, use Fortify for backend auth and
Laravel Themer for views and presets.

## Tutorial
Here is the video for **[Laravel Themer Tutorial](https://www.youtube.com/watch?v=Ty4ZwFTLYXE)**.

## Installation and setup

> **_NOTE:_**
>
> Laravel Themer v2.x and the above versions support **Vite**.
> If you want to use **Laravel Mix** then try **[Laravel Themer v1.7.1](https://github.com/qirolab/laravel-themer/tree/1.7.1 "v1.7.1")**

You can install this package via composer using:
```bash
composer require qirolab/laravel-themer
```

Publish a configuration file:
```bash
php artisan vendor:publish --provider="Qirolab\Theme\ThemeServiceProvider" --tag="config"
```

## Creating a theme

Run the following command in the terminal:
```bash
php artisan make:theme
```
This command will ask you to enter theme name, CSS framework, js framework, and optional auth scaffolding.

<img src="https://i.imgur.com/HDhORv1.png" alt="Create theme" />

## Useful Theme methods:

```php
// Set active theme
Theme::set('theme-name');

// Get current active theme
Theme::active();

// Get current parent theme
Theme::parent();

// Clear theme. So, no theme will be active
Theme::clear();

// Get theme path
Theme::path($path = 'views');
// output:
// /app-root-path/themes/active-theme/views

Theme::path($path = 'views', $themeName = 'admin');
// output:
// /app-root-path/themes/admin/views

Theme::getViewPaths();
// Output:
// [
//     '/app-root-path/themes/admin/views',
//     '/app-root-path/resources/views'
// ]

```

## Middleware to set a theme
Register `ThemeMiddleware` in `app\Http\Kernel.php`:

```php
protected $routeMiddleware = [
    // ...
    'theme' => \Qirolab\Theme\Middleware\ThemeMiddleware::class,
];
```
Examples for middleware usage:
```php
// Example 1: set theme for a route
Route::get('/dashboard', 'DashboardController@index')
    ->middleware('theme:dashboard-theme');


// Example 2: set theme for a route-group
Route::group(['middleware'=>'theme:admin-theme'], function() {
    // "admin-theme" will be applied to all routes defined here
});


// Example 3: set child and parent theme
Route::get('/dashboard', 'DashboardController@index')
    ->middleware('theme:child-theme,parent-theme');
```

## Asset compilation
 To compile the theme assets, first you need to add the following lines in the `scripts` section of the `package.json` file.

```
"scripts": {
    ...

    "dev:theme-name": "vite --config themes/theme-name/vite.config.js",
    "build:theme-name": "vite build --config themes/theme-name/vite.config.js"
}
```
Now, to compile a particular theme run the following command:

```bash
npm run dev:theme-name

# or

npm run build:theme-name
```

## Testing

```bash
composer test
```

## Support us
We invest a lot of resources into video tutorials and creating open-source packages. If you like what I do or if you ever made use of something I built or from my videos, consider supporting us. This will allow us to focus even more time on the tutorials and open-source projects we're working on.

<a href="https://www.buymeacoffee.com/qirolab" target="_blank"><img
src="https://i.imgur.com/zHowozE.png" alt="Buy Me A Coffee" style="height: 60px
!important; width: 217px !important;"></a>

Thank you so much for helping us out! 🥰

[![Spec Coder](https://i.imgur.com/lqkt7a3.png)](https://qirolab.com/spec-coder)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits
Authentication scaffolding stubs and presets are taken from [laravel/ui](https://github.com/laravel/ui), [laravel/breeze](https://github.com/laravel/breeze), and [laravel-frontend-presets/tailwindcss](https://github.com/laravel-frontend-presets/tailwindcss).

- [Harish Kumar](https://github.com/hkp22)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
