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
    protected function ensureDirectoryExists($path, $mode = 0755, $recursive = true)
    {
        if (! (new Filesystem)->isDirectory($path)) {
            (new Filesystem)->makeDirectory($path, $mode, $recursive);
        }
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string $search
     * @param  string $replace
     * @param  string $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Copy a directory from one location to another.
     *
     * @param  string   $directory
     * @param  string   $destination
     * @param  int|null $options
     * @return bool
     */
    public function copyDirectory($directory, $destination, $options = null)
    {
        return (new Filesystem)->copyDirectory($directory, $destination, $options);
    }

    /**
     * Determine if a file or directory exists.
     *
     * @param  string $path
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Append to a file.
     *
     * @param  string $path
     * @param  string $data
     * @return int
     */
    public function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }
}
