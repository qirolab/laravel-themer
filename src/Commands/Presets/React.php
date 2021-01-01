<?php

namespace Qirolab\Theme\Commands\Presets;

use Qirolab\Theme\Theme;

class React
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
            '@babel/preset-react' => '^7.12.10',
            'react' => '^17.0.1',
            'react-dom' => '^17.0.1',
        ] + $packages;

        // return [
        //     '@babel/preset-react' => '^7.0.0',
        //     'react' => '^16.2.0',
        //     'react-dom' => '^16.2.0',
        // ] + Arr::except($packages, ['vue', 'vue-template-compiler']);
    }

    /**
     * Update the Webpack configuration.
     *
     * @return $this
     */
    protected function updateWebpackConfiguration()
    {
        copy(__DIR__ . '/react-stubs/webpack.mix.js', Theme::path('webpack.mix.js', $this->theme));

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

        copy(__DIR__ . '/react-stubs/app.js', Theme::path('js/app.js', $this->theme));

        if (! $this->exists(Theme::path('js/bootstrap.js', $this->theme))) {
            copy(__DIR__ . '/react-stubs/bootstrap.js', Theme::path('js/bootstrap.js', $this->theme));
        }

        if (! $this->exists(base_path('.babelrc'))) {
            copy(__DIR__ . '/react-stubs/.babelrc', base_path('.babelrc'));
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
            __DIR__ . '/react-stubs/Example.js',
            Theme::path('js/components/Example.js', $this->theme)
        );

        return $this;
    }
}
