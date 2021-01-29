<?php

namespace Qirolab\Theme\Tests;

use Illuminate\Support\Facades\Route;
use Qirolab\Theme\Middleware\ThemeMiddleware;
use Qirolab\Theme\Theme;
use Qirolab\Theme\ThemeServiceProvider;

class ThemeMiddlewareTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('/admin', function () {
            return 'ok';
        })->middleware('theme:admin');

        Route::get('/frontend', function () {
            return 'ok';
        })->middleware('theme:frontend,parent');
    }

    protected function getPackageProviders($app)
    {
        $app['router']->aliasMiddleware('theme', ThemeMiddleware::class);
        $app['config']->set('theme.active', 'default');

        return [
            ThemeServiceProvider::class,
        ];
    }

    /** @test **/
    public function it_may_set_active_theme()
    {
        $this->assertEquals(Theme::active(), 'default');

        $this->get('/admin')
            ->assertSeeText('ok')
            ->assertStatus(200);

        $this->assertEquals(Theme::active(), 'admin');
    }

    /** @test **/
    public function it_may_set_parent_theme()
    {
        $this->assertEquals(Theme::active(), 'default');
        $this->assertEquals(Theme::parent(), null);

        $this->get('/frontend')
            ->assertSeeText('ok')
            ->assertStatus(200);

        $this->assertEquals(Theme::active(), 'frontend');
        $this->assertEquals(Theme::parent(), 'parent');
    }
}
