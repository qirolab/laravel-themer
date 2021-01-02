<?php

namespace Qirolab\Theme\Tests;

use Illuminate\Support\Facades\View;
use Qirolab\Theme\Theme;

class ParentThemeTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('theme.active', 'child-theme');
        $app['config']->set('theme.parent', 'parent-theme');
    }

    /** @test **/
    public function it_may_have_parent_theme()
    {
        $theme = config('theme.active');
        $parentTheme = config('theme.parent');

        $this->assertEquals(Theme::viewPath($theme), View::getFinder()->getPaths()[0]);
        $this->assertEquals(Theme::viewPath($parentTheme), View::getFinder()->getPaths()[1]);
        $this->assertCount(3, View::getFinder()->getPaths());
    }

    /** @test **/
    public function it_may_switch_parent_theme()
    {
        $theme = config('theme.active');
        $parentTheme = config('theme.parent');

        $this->assertEquals(Theme::viewPath($theme), View::getFinder()->getPaths()[0]);
        $this->assertEquals(Theme::viewPath($parentTheme), View::getFinder()->getPaths()[1]);
        $this->assertCount(3, View::getFinder()->getPaths());

        // switch theme
        $theme = 'admin';
        $parentTheme = 'parent-admin';
        Theme::set($theme, $parentTheme);

        $this->assertEquals(Theme::viewPath($theme), View::getFinder()->getPaths()[0]);
        $this->assertEquals(Theme::viewPath($parentTheme), View::getFinder()->getPaths()[1]);
        $this->assertCount(3, View::getFinder()->getPaths());
    }

    /** @test **/
    public function it_returns_parent_theme_name()
    {
        $this->assertEquals(config('theme.parent'), Theme::parent());

        // switch theme
        $theme = 'admin';
        $parentTheme = 'parent-admin';
        Theme::set($theme, $parentTheme);

        $this->assertEquals($theme, Theme::active());
        $this->assertEquals($parentTheme, Theme::parent());
    }

    /** @test **/
    public function if_theme_is_disabled_then_active_theme_returns_null()
    {
        // $this->app['view']->setFinder($this->app['view.finder']);
        app()->forgetInstance('theme.finder');

        $this->assertNull(Theme::parent());
    }

    /** @test **/
    public function it_removes_previous_parent_theme_path_on_switch_theme()
    {
        $theme = config('theme.active');
        $parentTheme = config('theme.parent');
        $previousThemePath = Theme::viewPath($theme);
        $previousParentTheme = Theme::viewPath($parentTheme);

        $this->assertEquals($previousThemePath, View::getFinder()->getPaths()[0]);
        $this->assertEquals($previousParentTheme, View::getFinder()->getPaths()[1]);
        $this->assertCount(3, View::getFinder()->getPaths());

        // switch theme
        $theme = 'admin';
        $parentTheme = 'parent-admin';
        Theme::set($theme, $parentTheme);

        // new theme path in the view finder
        $this->assertEquals(Theme::viewPath($theme), View::getFinder()->getPaths()[0]);
        $this->assertEquals(Theme::viewPath($parentTheme), View::getFinder()->getPaths()[1]);

        // previous path is removed in the view finder
        $this->assertFalse(in_array($previousThemePath, View::getFinder()->getPaths()));
        $this->assertFalse(in_array($previousParentTheme, View::getFinder()->getPaths()));

        // total paths in the view finder
        $this->assertCount(3, View::getFinder()->getPaths());
    }

    /** @test **/
    public function it_removes_parent_theme_on_switch_only_active_theme()
    {
        $theme = config('theme.active');
        $parentTheme = config('theme.parent');
        $previousThemePath = Theme::viewPath($theme);
        $previousParentTheme = Theme::viewPath($parentTheme);

        $this->assertEquals($previousThemePath, View::getFinder()->getPaths()[0]);
        $this->assertEquals($previousParentTheme, View::getFinder()->getPaths()[1]);
        $this->assertCount(3, View::getFinder()->getPaths());

        // switch active theme
        $theme = 'admin';
        Theme::set($theme);

        // new theme path in the view finder
        $this->assertEquals(Theme::viewPath($theme), View::getFinder()->getPaths()[0]);

        // previous path is removed in the view finder
        $this->assertFalse(in_array($previousThemePath, View::getFinder()->getPaths()));
        $this->assertFalse(in_array($previousParentTheme, View::getFinder()->getPaths()));

        // total paths in the view finder
        $this->assertCount(2, View::getFinder()->getPaths());
    }
}
