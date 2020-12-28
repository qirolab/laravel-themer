<?php

namespace Qirolab\Theme\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Qirolab\Theme\ThemeServiceProvider;
use Illuminate\Filesystem\Filesystem;

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
}