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
            '@tailwindcss/forms' => '^0.4.0',
            'autoprefixer' => '^10.4.2',
            'postcss' => '^8.4.5',
            'postcss-import' => '^14.0.2',
            'tailwindcss' => '^3.0.13',
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

        copy(__DIR__.'/../../stubs/Presets/tailwind-stubs/tailwind.config.js', $this->themePath('tailwind.config.js'));

        if (! $this->exists($this->themePath('js/app.js'))) {
            copy(__DIR__.'/../../stubs/Presets/tailwind-stubs/js/app.js', $this->themePath('js/app.js'));
        }

        copy(__DIR__.'/../../stubs/Presets/tailwind-stubs/css/app.css', $this->themePath('css/app.css'));

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
