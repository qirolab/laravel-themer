<?php

namespace Qirolab\Theme\Presets\Vite;

use Qirolab\Theme\Presets\Traits\PresetTrait;
use Qirolab\Theme\Presets\Traits\StubTrait;

class BootstrapPreset
{
    use PresetTrait;
    use StubTrait;

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

        copy(
            $this->stubPath('bootstrap-stubs/js/bootstrap.js'),
            $this->themePath('js/bootstrap.js')
        );
        copy(
            $this->stubPath('bootstrap-stubs/js/app.js'),
            $this->themePath('js/app.js')
        );

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

        copy(
            $this->stubPath('bootstrap-stubs/sass/_variables.scss'),
            $this->themePath('sass/_variables.scss')
        );
        copy(
            $this->stubPath('bootstrap-stubs/sass/app.scss'),
            $this->themePath('sass/app.scss')
        );

        return $this;
    }

    public function updateViteConfig($configData)
    {
        $configData = str_replace('%app_css_input%', 'sass/app.scss', $configData);
        $bootstrap = "'~bootstrap': path.resolve('node_modules/bootstrap'),";

        return str_replace('%bootstrap%', $bootstrap, $configData);
    }
}
