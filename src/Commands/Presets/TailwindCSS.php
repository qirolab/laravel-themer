<?php

namespace Qirolab\Theme\Commands\Presets;

use Qirolab\Theme\Theme;

class TailwindCSS
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
            '@tailwindcss/forms' => '^0.2.1',
            'postcss-import' => '^12.0.1',
            'tailwindcss' => 'npm:@tailwindcss/postcss7-compat@^2.0.2',
            'autoprefixer' => '^9.8.6',
        ] + $packages;
    }

    /**
     * Update the Webpack configuration.
     *
     * @return $this
     */
    protected function updateWebpackConfiguration()
    {
        // @unlink(Theme::path('webpack.mix.js', $this->theme));

        copy(__DIR__ . '/tailwind-stubs/webpack.mix.js', Theme::path('webpack.mix.js', $this->theme));

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
        $this->ensureDirectoryExists(Theme::path('js', $this->theme));
        $this->ensureDirectoryExists(Theme::path('css', $this->theme));

        if (! $this->exists(base_path('tailwind.config.js'))) {
            // copy(__DIR__ . '/tailwind-stubs/tailwind.config.js', Theme::path('tailwind.config.js', $this->theme));
            copy(__DIR__ . '/tailwind-stubs/tailwind.config.js', base_path('tailwind.config.js'));
        }

        if (! $this->exists(Theme::path('js/app.js', $this->theme))) {
            copy(__DIR__ . '/tailwind-stubs/js/app.js', Theme::path('js/app.js', $this->theme));
        }

        copy(__DIR__ . '/tailwind-stubs/css/app.css', Theme::path('css/app.css', $this->theme));

        return $this;
    }
}
