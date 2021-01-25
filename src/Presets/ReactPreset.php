<?php

namespace Qirolab\Theme\Presets;

use Qirolab\Theme\Presets\Traits\PresetTrait;

class ReactPreset
{
    use PresetTrait;

    public function export(): void
    {
        $this->updatePackages()
            ->exportReactComponent()
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
     * Update the bootstrapping files.
     *
     * @return $this
     */
    protected function exportJs()
    {
        copy(__DIR__ . '/../../stubs/Presets/react-stubs/app.js', $this->themePath('js/app.js'));

        if (! $this->exists($this->themePath('js/bootstrap.js'))) {
            copy(__DIR__ . '/../../stubs/Presets/react-stubs/bootstrap.js', $this->themePath('js/bootstrap.js'));
        }

        // if (!$this->exists(base_path('.babelrc'))) {
        //     copy(__DIR__ . '/../../stubs/Presets/react-stubs/.babelrc', base_path('.babelrc'));
        // }

        return $this;
    }

    /**
     * Update the example component.
     *
     * @return $this
     */
    protected function exportReactComponent()
    {
        $this->ensureDirectoryExists($this->themePath('js/components'));

        copy(
            __DIR__ . '/../../stubs/Presets/react-stubs/Example.js',
            $this->themePath('js/components/Example.js')
        );

        return $this;
    }

    public function webpackJs()
    {
        if ($mixVersion = $this->getMixVersion()) {
            if (version_compare($mixVersion, '6.0.0', '<')) {
                return '.js(`${__dirname}/js/app.js`, "js")
    .babelConfig({
        presets: ["@babel/preset-react"]
    })';
            }

            $jsMix = '.js(`${__dirname}/js/app.js`, "js")';
            $jsMix .= "\n    .react()";

            return $jsMix;
        }
    }
}
