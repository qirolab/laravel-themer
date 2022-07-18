<?php

namespace Qirolab\Theme\Presets\Vite;

use Qirolab\Theme\Presets\Traits\PresetTrait;
use Qirolab\Theme\Presets\Traits\StubTrait;

class ReactPreset
{
    use PresetTrait;
    use StubTrait;

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
            '@vitejs/plugin-react' => '^1.3.2',
            // '@babel/preset-react' => '^7.18.6',
            'react' => '^18.2.0',
            'react-dom' => '^18.2.0',
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
        copy($this->stubPath('react-stubs/app.js'), $this->themePath('js/app.js'));

        if (! $this->exists($this->themePath('js/bootstrap.js'))) {
            copy($this->stubPath('react-stubs/bootstrap.js'), $this->themePath('js/bootstrap.js'));
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
            $this->stubPath('react-stubs/Example.jsx'),
            $this->themePath('js/components/Example.jsx')
        );

        return $this;
    }

    public function updateViteConfig($configData)
    {
        $reactImport = "import react from '@vitejs/plugin-react';";
        $reactConfig = 'react(),';

        $configData = str_replace('%react_import%', $reactImport, $configData);
        $configData = str_replace('%react_plugin_config%', $reactConfig, $configData);

        return $configData;
    }
}
