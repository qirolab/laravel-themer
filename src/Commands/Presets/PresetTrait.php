<?php

namespace Qirolab\Theme\Commands\Presets;

use Illuminate\Filesystem\Filesystem;
use Qirolab\Theme\Theme;
use Qirolab\Theme\Trails\HandleFiles;

trait PresetTrait
{
    use HandleFiles;

    /**
     * @var string
     */
    protected $theme;

    /**
     * @var string
     */
    protected $themePath;

    public function __construct(string $theme)
    {
        $this->theme = $theme;

        $this->themePath = Theme::path('', $theme);

        $this->ensureDirectoryExists($this->themePath);
    }

    /**
     * Install the preset.
     *
     * @return void
     */
    public function install()
    {
        $this->updatePackages();

        $this->updateWebpackConfiguration()
            ->updateBootstrapping()
            ->removeNodeModules();
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
     * Remove the installed Node modules.
     *
     * @return $this
     */
    protected function removeNodeModules()
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
        });

        return $this;
    }
}
