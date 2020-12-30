<?php

namespace Qirolab\Theme\Trails;

use Illuminate\Filesystem\Filesystem;

trait HandleFiles
{
    /**
     * Ensure a directory exists.
     *
     * @param  string  $path
     * @param  int  $mode
     * @param  bool  $recursive
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
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
