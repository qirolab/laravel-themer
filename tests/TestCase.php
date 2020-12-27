<?php

namespace Qirolab\Theme\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Qirolab\Theme\ThemeServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ThemeServiceProvider::class,
        ];
    }
}
