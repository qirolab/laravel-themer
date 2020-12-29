<?php

namespace Qirolab\Theme\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase as Orchestra;
use Qirolab\Theme\ThemeServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        (new Filesystem)->cleanDirectory(resource_path('views'));
        (new Filesystem)->deleteDirectory(base_path('themes'));
    }

    protected function getPackageProviders($app)
    {
        return [
            ThemeServiceProvider::class,
        ];
    }

    public function ensureDirectoryExists($path, $mode = 0755, $recursive = true)
    {
        if (! (new Filesystem)->isDirectory($path)) {
            (new Filesystem)->makeDirectory($path, $mode, $recursive);
        }
    }
}
