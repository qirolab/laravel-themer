# Changelog

All notable changes to `laravel-themer` will be documented in this file.

## 1.4.4 - 2021-02-20
- Bug fix theme solution provider

## 1.4.3 - 2021-02-01
- Video links updated in the `ThemeSolutionProvider`

## 1.4.2 - 2021-01-29
- `Theme::getViewPaths();` method added
- Bug fix on register theme service provider

## 1.4.1 - 2021-01-28
- Validate Vue version, if a specific Vue version is installed then cannot generate a theme for other Vue version.

## 1.4.0 - 2021-01-26
- Added Vue 3 Preset

## 1.3.0 - 2021-01-26
- Refactor code for preset export
- Add `babelConfig` in `webpack.mix.js` for `preset-react` for Mix version 5.x
- Load `tailwind.config.js` from theme directory

## 1.2.3 - 2021-01-23

- Refactoring `AuthScaffolding` trait
- `ThemeBasePathNotDefined` exception

## 1.2.2 - 2021-01-05

- output message on theme command

## 1.2.1 - 2021-01-03

- Documentation link update

## 1.2.0 - 2021-01-03

- theme middleware to set active theme

## 1.1.0 - 2021-01-03

- code refactoring

## 1.0.0 - 2021-01-02

- initial release
