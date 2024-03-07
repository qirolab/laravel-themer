<?php

namespace Qirolab\Theme\Presets\Traits;

use Illuminate\Filesystem\Filesystem;

trait HandleFiles
{
    /**
     * Ensure a directory exists.
     *
     * @param  string $path
     * @param  int    $mode
     * @param  bool   $recursive
     * @return void
     */
    protected function ensureDirectoryExists(string $path, int $mode = 0755, bool $recursive = true)
    {
        if (! (new Filesystem())->isDirectory($path)) {
            (new Filesystem())->makeDirectory($path, $mode, $recursive);
        }
    }

    protected function replaceInFile(string $search, string $replace, string $path): bool
    {
        return (bool) file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    public function createFile(string $path, string $content = ''): bool
    {
        return (bool) file_put_contents($path, $content);
    }

    public function append(string $path, string $data): bool
    {
        return (bool) file_put_contents($path, $data, FILE_APPEND);
    }

    public function copyDirectory(string $directory, string $destination, $options = null): bool
    {
        return (new Filesystem())->copyDirectory($directory, $destination, $options);
    }

    public function exists(string $path): bool
    {
        return file_exists($path);
    }
}
