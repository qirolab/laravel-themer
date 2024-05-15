<?php

namespace Qirolab\Theme\Presets\Vite;

use Qirolab\Theme\Presets\Traits\PresetTrait;
use Qirolab\Theme\Presets\Traits\StubTrait;

class Vue3Preset
{
    use PresetTrait;
    use StubTrait;

    public function export(): void
    {
        $this->updatePackages()
            ->exportVueComponent()
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
            '@vitejs/plugin-vue' => '^5.0.4',
            // '@vue/compiler-sfc' => '^3.2.37',
            // 'resolve-url-loader' => '^5.0.0',
            // 'sass' => '^1.53.0',
            // 'sass-loader' => '^13.0.2',
            'vue' => '^3.4.27',
            // 'vue-loader' => '^17.0.0',
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
        copy($this->stubPath('vue3-stubs/app.js'), $this->themePath('js/app.js'));

        if (! $this->exists($this->themePath('js/bootstrap.js'))) {
            copy($this->stubPath('vue3-stubs/bootstrap.js'), $this->themePath('js/bootstrap.js'));
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
            $this->stubPath('vue3-stubs/ExampleComponent.vue'),
            $this->themePath('js/components/ExampleComponent.vue')
        );

        return $this;
    }

    public function updateViteConfig($configData)
    {
        $vueImport = "import vue from '@vitejs/plugin-vue';";
        $vueConfig = 'vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),';

        $configData = str_replace('%vue_import%', $vueImport, $configData);
        $configData = str_replace('%vue_plugin_config%', $vueConfig, $configData);

        return $configData;
    }
}
