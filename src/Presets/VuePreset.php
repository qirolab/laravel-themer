<?php

namespace Qirolab\Theme\Presets;

use Qirolab\Theme\Presets\Traits\PresetTrait;

class VuePreset
{
    use PresetTrait;

    public function export(): void
    {
        $this->updatePackages()
            ->exportJs()
            ->exportVueComponent();
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
     * Update the bootstrapping files.
     *
     * @return $this
     */
    protected function exportJs()
    {
        copy(__DIR__ . '/../../stubs/Presets/vue-stubs/app.js', $this->themePath('js/app.js'));

        if (! $this->exists($this->themePath('js/bootstrap.js'))) {
            copy(__DIR__ . '/../../stubs/Presets/vue-stubs/bootstrap.js', $this->themePath('js/bootstrap.js'));
        }

        return $this;
    }

    /**
     * Update the example component.
     *
     * @return $this
     */
    protected function exportVueComponent()
    {
        $this->ensureDirectoryExists($this->themePath('js/components'));

        copy(
            __DIR__ . '/../../stubs/Presets/vue-stubs/ExampleComponent.vue',
            $this->themePath('js/components/ExampleComponent.vue')
        );

        return $this;
    }

    /**
     * @return null|string
     */
    public function webpackJs()
    {
        if ($mixVersion = $this->getMixVersion()) {
            if (version_compare($mixVersion, '6.0.0', '<')) {
                return '.js(`${__dirname}/js/app.js`, "js")';
            }

            $jsMix = '.js(`${__dirname}/js/app.js`, "js")';
            $jsMix .= "\n    .vue()";

            return $jsMix;
        }
    }
}
