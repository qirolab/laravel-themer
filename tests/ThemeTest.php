<?php

namespace Qirolab\Theme\Tests;

use Illuminate\Support\Facades\View;
use Qirolab\Theme\Theme;
use Qirolab\Theme\ThemeServiceProvider;
use Qirolab\Theme\ThemeViewFinder;

class ThemeTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        $app['config']->set('theme.active', 'default');

        return [
            ThemeServiceProvider::class,
        ];
    }

    /** @test **/
    public function it_returns_theme_finder_class()
    {
        $this->assertInstanceOf(ThemeViewFinder::class, Theme::finder());
    }

    /** @test **/
    public function it_returns_theme_view_path()
    {
        // theme from config
        $theme = config('theme.active');
        $path = config('theme.base_path') . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . 'views';
        $path = realpath($path) ?: $path;
        $this->assertEquals($path, Theme::viewPath());

        // admin theme
        $theme = 'admin';
        $path = config('theme.base_path') . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . 'views';
        $path = realpath($path) ?: $path;
        $this->assertEquals($path, Theme::viewPath($theme));
    }

    /** @test **/
    public function it_returns_theme_path()
    {
        $theme = config('theme.active');
        $path = config('theme.base_path') . DIRECTORY_SEPARATOR . $theme;
        $this->assertEquals($path, Theme::path());

        $path = $path . DIRECTORY_SEPARATOR . 'views';
        $this->assertEquals($path, Theme::path('views'));

        $path = config('theme.base_path') . DIRECTORY_SEPARATOR . 'admin';
        $this->assertEquals($path, Theme::path('', 'admin'));

        $path = $path . DIRECTORY_SEPARATOR . 'views/vendors';
        $this->assertEquals($path, Theme::path('views/vendors', 'admin'));
    }

    /** @test **/
    public function it_includes_theme_view_path_in_the_laravel_view_finder_paths()
    {
        $this->assertCount(2, View::getFinder()->getPaths());

        $this->assertEquals(Theme::viewPath(), View::getFinder()->getPaths()[0]);
    }

    /** @test **/
    public function it_can_set_new_theme()
    {
        $theme = config('theme.active');
        $this->assertEquals(Theme::viewPath($theme), View::getFinder()->getPaths()[0]);
        $this->assertEquals($theme, Theme::finder()->getActiveTheme());

        $theme = 'admin';
        Theme::set($theme);
        $this->assertEquals(Theme::viewPath($theme), View::getFinder()->getPaths()[0]);
        $this->assertEquals($theme, Theme::finder()->getActiveTheme());
    }

    /** @test **/
    public function it_returns_active_theme_name()
    {
        $this->assertEquals(config('theme.active'), Theme::active());

        Theme::set('admin');
        $this->assertEquals('admin', Theme::active());
    }

    /** @test **/
    public function it_removes_previous_theme_path_from_laravel_view_finder_paths()
    {
        $theme = config('theme.active');
        $previousPath = Theme::viewPath($theme);
        $this->assertEquals($previousPath, View::getFinder()->getPaths()[0]);
        $this->assertCount(2, View::getFinder()->getPaths());

        $theme = 'admin';
        Theme::set($theme);

        // new theme path in the view finder
        $this->assertEquals(Theme::viewPath($theme), View::getFinder()->getPaths()[0]);

        // previous path is removed in the view finder
        $this->assertFalse(in_array($previousPath, View::getFinder()->getPaths()));

        // total paths in the view finder
        $this->assertCount(2, View::getFinder()->getPaths());
    }

    /** @test **/
    public function on_change_view_finder_active_theme_returns_null()
    {
        // $this->app['view']->setFinder($this->app['view.finder']);
        app()->forgetInstance('theme.finder');

        $this->assertNull(Theme::active());
    }

    /** @test **/
    public function it_returns_all_view_paths()
    {
        $this->assertCount(2, Theme::getViewPaths());

        Theme::set('child', 'parent');

        $this->assertCount(3, Theme::getViewPaths());
    }
}
