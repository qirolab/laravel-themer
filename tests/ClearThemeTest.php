<?php

namespace Qirolab\Theme\Tests;

use Illuminate\Support\Facades\View;
use Qirolab\Theme\Theme;

class ClearThemeTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('theme.parent', 'parent');
    }

    /** @test **/
    public function it_may_clear_theme()
    {
        $theme = config('theme.active');
        $parentTheme = config('theme.parent');
        $previousThemePath = Theme::path($theme);
        $previousParentTheme = Theme::path($parentTheme);

        $this->assertEquals($previousThemePath, View::getFinder()->getPaths()[0]);
        $this->assertEquals($previousParentTheme, View::getFinder()->getPaths()[1]);
        $this->assertCount(3, View::getFinder()->getPaths());
        $this->assertEquals($theme, Theme::active());
        $this->assertEquals($parentTheme, Theme::parent());

        // clear themes
        Theme::clear();

        // previous paths are removed in the view finder
        $this->assertFalse(in_array($previousThemePath, View::getFinder()->getPaths()));
        $this->assertFalse(in_array($previousParentTheme, View::getFinder()->getPaths()));

        $this->assertNull(Theme::active());
        $this->assertNull(Theme::parent());

        $this->assertCount(1, View::getFinder()->getPaths());
    }
}
