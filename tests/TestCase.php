<?php

namespace Qirolab\Theme\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase as Orchestra;
use Qirolab\Theme\ThemeServiceProvider;
use Qirolab\Theme\Trails\HandleFiles;

class TestCase extends Orchestra
{
    use HandleFiles;

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
}
