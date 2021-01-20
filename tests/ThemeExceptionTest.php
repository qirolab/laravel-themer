<?php

namespace Qirolab\Theme\Tests;

use Qirolab\Theme\Exceptions\ThemeBasePathNotDefined;
use Qirolab\Theme\Theme;

class ThemeExceptionTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('theme.base_path', null);
    }

    /** @test **/
    public function it_throws_theme_base_path_not_defined_exception_if_base_path_is_null()
    {
        $this->expectException(ThemeBasePathNotDefined::class);

        Theme::set('admin');
    }
}
