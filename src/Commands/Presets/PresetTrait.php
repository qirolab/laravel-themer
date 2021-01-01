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

        $packages = $this->getPackageScripts($packages);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );

        return $this;
    }

    protected function getPackageScripts($packages)
    {
        $mixVersion = $packages['devDependencies']['laravel-mix'] ?? null;
        $mixVersion = $this->getVersion($mixVersion);

        if ($mixVersion) {
            if (version_compare($mixVersion, '6.0.0', '<')) {
                $packages['scripts'] = [
                    'dev' => 'npm run development',
                    'development' => 'cross-env theme=$npm_config_theme NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --config=node_modules/laravel-mix/setup/webpack.config.js',
                    'watch' => 'npm run development -- --watch',
                    'watch-poll' => 'npm run watch -- --watch-poll',
                    'hot' => 'cross-env theme=$npm_config_theme NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --disable-host-check --config=node_modules/laravel-mix/setup/webpack.config.js',
                    'prod' => 'npm run production',
                    'production' => 'cross-env theme=$npm_config_theme NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --config=node_modules/laravel-mix/setup/webpack.config.js',
                ];
            }
        }

        return $packages;
    }

    protected function getVersion($str)
    {
        preg_match("/\s*((?:[0-9]+\.?)+)/i", $str, $matches);

        return $matches[1];
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
