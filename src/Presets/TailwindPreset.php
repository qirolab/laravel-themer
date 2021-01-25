<?php

namespace Qirolab\Theme\Presets;

use Qirolab\Theme\Presets\Traits\PresetTrait;

class TailwindPreset
{
    use PresetTrait;

    public function export(): void
    {
        $this->updatePackages()
            ->exportBootstrapping();
    }

    /**
     * Update the given package array.
     *
     * @param  array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages): array
    {
        return [
            '@tailwindcss/forms' => '^0.2.1',
            'postcss-import' => '^12.0.1',
            'tailwindcss' => 'npm:@tailwindcss/postcss7-compat@^2.0.2',
            'autoprefixer' => '^9.8.6',
        ] + $packages;
    }

    /**
     * Update the bootstrapping files.
     *
     * @return $this
     */
    protected function exportBootstrapping()
    {
        $this->ensureDirectoryExists($this->themePath('js'));
        $this->ensureDirectoryExists($this->themePath('css'));

        copy(__DIR__ . '/../../stubs/Presets/tailwind-stubs/tailwind.config.js', $this->themePath('tailwind.config.js'));

        if (! $this->exists($this->themePath('js/app.js'))) {
            copy(__DIR__ . '/../../stubs/Presets/tailwind-stubs/js/app.js', $this->themePath('js/app.js'));
        }

        copy(__DIR__ . '/../../stubs/Presets/tailwind-stubs/css/app.css', $this->themePath('css/app.css'));

        return $this;
    }

    public function webpackJs(): string
    {
        if ($this->jsPreset() && method_exists($this->jsPreset(), 'webpackJs')) {
            return $this->jsPreset()->webpackJs();
        }

        return '.js(`${__dirname}/js/app.js`, "js")';
    }

    public function webpackCss(): string
    {
        return '.postCss(`${__dirname}/css/app.css`, "css", [
        require("postcss-import"),
        require("tailwindcss")({
            config: `${__dirname}/tailwind.config.js`,
        }),
        require("autoprefixer"),
    ])';
    }
}
