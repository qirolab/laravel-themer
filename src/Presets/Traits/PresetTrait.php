<?php

namespace Qirolab\Theme\Presets\Traits;

use Qirolab\Theme\Presets\PresetExport;
use Qirolab\Theme\Theme;

trait PresetTrait
{
    use HandleFiles;

    /**
     * @var PresetExport
     */
    public $exporter;

    public function __construct(PresetExport $exporter)
    {
        $this->exporter = $exporter;
    }

    public function getTheme(): string
    {
        return $this->exporter->getTheme();
    }

    public function themePath($path = '')
    {
        return Theme::path($path, $this->getTheme());
    }

    public function jsPreset()
    {
        return $this->exporter->jsPreset();
    }

    /**
     * Update the "package.json" file.
     *
     * @param bool $dev
     *
     * @return null|$this
     */
    protected function updatePackages($dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = static::updatePackageArray(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : []
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMixVersion()
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $mixVersion = $packages['devDependencies']['laravel-mix'] ?? null;

        return $this->getVersion($mixVersion);
    }

    /**
     * @param  string $str
     * @return string
     */
    protected function getVersion($str)
    {
        preg_match("/\s*((?:[0-9]+\.?)+)/i", $str, $matches);

        return $matches[1];
    }
}
