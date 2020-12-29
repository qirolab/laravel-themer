<?php

namespace Qirolab\Theme\Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Qirolab\Theme\Theme;

class ControllerViewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('/', function () {
            return view('dashboard');
        });
    }

    /** @test **/
    public function it_renders_view_from_active_theme()
    {
        $text = $this->publishBladeFile(config('theme.active'));

        $this->get('/')
            ->assertSeeText($text)
            ->assertStatus(200);
    }

    /** @test **/
    public function it_renders_view_from_parent_if_view_file_not_found_in_active_theme()
    {
        Theme::set('main', 'parent');

        $text = $this->publishBladeFile('parent');

        $this->get('/')
            ->assertSeeText($text)
            ->assertStatus(200);
    }

    /** @test **/
    public function it_renders_view_from_default_laravel_views_if_view_file_not_found_in_active_or_parent_theme()
    {
        Theme::set('main-theme', 'parent-theme');

        $text = $this->publishBladeFile();

        $this->get('/')
            ->assertSeeText($text)
            ->assertStatus(200);
    }

    public function publishBladeFile($theme = null)
    {
        if ($theme) {
            $text = "From theme {$theme}";
            $viewPath = Theme::path($theme);
        } else {
            $text = 'From default laravel views';
            $paths = View::getFinder()->getPaths();
            $viewPath = end($paths);
        }

        $bladeFilePath = $viewPath . DIRECTORY_SEPARATOR . 'dashboard.blade.php';

        $this->ensureDirectoryExists($viewPath);
        (new Filesystem)->put($bladeFilePath, $text);

        return $text;
    }
}