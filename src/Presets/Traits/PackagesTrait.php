<?php

namespace Qirolab\Theme\Presets\Traits;

trait PackagesTrait
{
    public function getPackages()
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        return json_decode(file_get_contents(base_path('package.json')), true);
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
        if ($packages = $this->getPackages()) {
            $configurationKey = $dev ? 'devDependencies' : 'dependencies';

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

        return null;
    }

    /**
     * @param  string      $package
     * @param  bool     $dev
     * @return null|string
     */
    public function getPackageVersion($package, $dev = true)
    {
        if ($packages = $this->getPackages()) {
            $configurationKey = $dev ? 'devDependencies' : 'dependencies';

            $version = $packages[$configurationKey][$package] ?? null;

            return $this->getVersion($version);
        }

        return null;
    }

    /**
    * @param  string $str
    * @return null|string
    */
    protected function getVersion($str)
    {
        preg_match("/\s*((?:[0-9]+\.?)+)/i", $str, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param  bool     $dev
     * @return null|string
     */
    public function getMixVersion($dev = true)
    {
        return $this->getPackageVersion('laravel-mix', $dev);
    }

    /**
     * @param  bool     $dev
     * @return null|string
     */
    public function getVueVersion($dev = true)
    {
        return $this->getPackageVersion('vue', $dev);
    }

    /**
     * @param  string  $actual
     * @param  string  $compare
     * @return bool
     */
    public function versionLessThan($actual, $compare)
    {
        return version_compare($actual, $compare, '<');
    }

    /**
     * @param  string  $actual
     * @param  string  $compare
     * @return bool
     */
    public function versionGreaterThan($actual, $compare)
    {
        return version_compare($actual, $compare, '>');
    }

    /**
     * @param  string  $actual
     * @param  string  $compare
     * @return bool
     */
    public function versionGreaterOrEqual($actual, $compare)
    {
        return version_compare($actual, $compare, '>=');
    }
}
