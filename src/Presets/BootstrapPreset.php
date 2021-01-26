<?php

namespace Qirolab\Theme\Presets;

use Qirolab\Theme\Presets\Traits\PresetTrait;

class BootstrapPreset
{
    use PresetTrait;

    public function export(): void
    {
        $this->updatePackages()
            ->exportSass()
            ->exportJs();
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
            'bootstrap' => '^4.6.0',
            'jquery' => '^3.5',
            'popper.js' => '^1.16',
            'sass' => '^1.32.1',
            'sass-loader' => '^10.1.1',
        ] + $packages;
    }

    /**
     * Update the bootstrapping files.
     *
     * @return $this
     */
    protected function exportJs()
    {
        $this->ensureDirectoryExists($this->themePath('js'));

        copy(__DIR__ . '/../../stubs/Presets/bootstrap-stubs/js/bootstrap.js', $this->themePath('js/bootstrap.js'));
        copy(__DIR__ . '/../../stubs/Presets/bootstrap-stubs/js/app.js', $this->themePath('js/app.js'));

        return $this;
    }

    /**
     * Update the Sass files for the application.
     *
     * @return $this
     */
    protected function exportSass()
    {
        $this->ensureDirectoryExists($this->themePath('sass'));

        copy(__DIR__ . '/../../stubs/Presets/bootstrap-stubs/sass/_variables.scss', $this->themePath('sass/_variables.scss'));
        copy(__DIR__ . '/../../stubs/Presets/bootstrap-stubs/sass/app.scss', $this->themePath('sass/app.scss'));

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
        return '.sass(`${__dirname}/sass/app.scss`, "css")';
    }
}
