<?php

namespace Qirolab\Theme\Commands\Presets;

use Qirolab\Theme\Theme;

class Vue
{
    use PresetTrait;

    /**
     * Update the given package array.
     *
     * @param  array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages): array
    {
        return [
            'resolve-url-loader' => '^2.3.1',
            'sass' => '^1.20.1',
            'sass-loader' => '^8.0.0',
            'vue' => '^2.5.17',
            'vue-template-compiler' => '^2.6.10',
        ] + $packages;

        // return [
        //     'resolve-url-loader' => '^2.3.1',
        //     'sass' => '^1.20.1',
        //     'sass-loader' => '^8.0.0',
        //     'vue' => '^2.5.17',
        //     'vue-template-compiler' => '^2.6.10',
        // ] + Arr::except($packages, [
        //     '@babel/preset-react',
        //     'react',
        //     'react-dom',
        // ]);
    }

    /**
     * Update the Webpack configuration.
     *
     * @return $this
     */
    protected function updateWebpackConfiguration()
    {
        copy(__DIR__ . '/vue-stubs/webpack.mix.js', Theme::path('webpack.mix.js', $this->theme));

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
        $this->updateComponent();

        copy(__DIR__ . '/vue-stubs/app.js', Theme::path('js/app.js', $this->theme));

        if (! $this->exists(Theme::path('js/bootstrap.js', $this->theme))) {
            copy(__DIR__ . '/vue-stubs/bootstrap.js', Theme::path('js/bootstrap.js', $this->theme));
        }

        return $this;
    }

    /**
     * Update the example component.
     *
     * @return $this
     */
    protected function updateComponent()
    {
        $this->ensureDirectoryExists(Theme::path('js/components', $this->theme));

        copy(
            __DIR__ . '/vue-stubs/ExampleComponent.vue',
            Theme::path('js/components/ExampleComponent.vue', $this->theme)
        );

        return $this;
    }
}
