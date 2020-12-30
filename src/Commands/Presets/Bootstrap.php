<?php

namespace Qirolab\Theme\Commands\Presets;

use Qirolab\Theme\Theme;

class Bootstrap
{
    use PresetTrait;

    /**
     * Update the given package array.
     *
     * @param  array  $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages): array
    {
        return [
            'bootstrap' => '^4.0.0',
            'jquery' => '^3.2',
            'popper.js' => '^1.12',
            'sass' => '^1.15.2',
            'sass-loader' => '^8.0.0',
        ] + $packages;
    }

    /**
     * Update the Webpack configuration.
     *
     * @return $this
     */
    protected function updateWebpackConfiguration()
    {
        copy(__DIR__ . '/bootstrap-stubs/webpack.mix.js', Theme::path('webpack.mix.js', $this->theme));

        $this->replaceInFile(
            '%theme%',
            $this->theme,
            Theme::path('webpack.mix.js', $this->theme)
        );

        return $this;
    }

    /**
     * Update the bootstrapping files.
     *
     * @return $this
     */
    protected function updateBootstrapping()
    {
        $this->updateSass();

        $this->ensureDirectoryExists(Theme::path('js', $this->theme));

        copy(__DIR__ . '/bootstrap-stubs/js/bootstrap.js', Theme::path('js/bootstrap.js', $this->theme));
        copy(__DIR__ . '/bootstrap-stubs/js/app.js', Theme::path('js/app.js', $this->theme));

        return $this;
    }

    /**
     * Update the Sass files for the application.
     *
     * @return $this
     */
    protected function updateSass()
    {
        $this->ensureDirectoryExists(Theme::path('sass', $this->theme));

        copy(__DIR__ . '/bootstrap-stubs/sass/_variables.scss', Theme::path('sass/_variables.scss', $this->theme));
        copy(__DIR__ . '/bootstrap-stubs/sass/app.scss', Theme::path('sass/app.scss', $this->theme));

        return $this;
    }
}
