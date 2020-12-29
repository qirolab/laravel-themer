<?php

namespace Qirolab\Theme\Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Qirolab\Theme\Theme;

class ViewNamespaceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('/', function () {
            return view('dashboard');
        });
    }

    /** @test **/
    public function it_renders_namespaced_view_from_the_active_theme()
    {
        $text = $this->publishBladeFile('main');
        $namespaceText = $this->publishNamespacedBladeFile('main');

        Theme::set('main');

        $this->get('/')
                ->assertSeeText($text)
                ->assertSeeText($namespaceText)
                ->assertStatus(200);
    }

    /** @test **/
    public function it_renders_namespaced_view_from_parent_if_view_file_not_found_in_active_theme()
    {
        $text = $this->publishBladeFile('main');
        $namespaceText = $this->publishNamespacedBladeFile('parent');

        Theme::set('main', 'parent');

        $this->get('/')
            ->assertSeeText($text)
            ->assertSeeText($namespaceText)
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
        (new Filesystem)->put($bladeFilePath, $text . ' @include("testing::hello")');

        return $text;
    }

    protected function publishNamespacedBladeFile($theme = null)
    {
        if ($theme) {
            $text = "From theme {$theme} namespace";
            $viewPath = Theme::path($theme);
        } else {
            $text = 'From default laravel views namespace';
            $paths = View::getFinder()->getPaths();
            $viewPath = end($paths);
        }

        $namespacePath = $viewPath . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'testing';
        $namespaceBadeFile = $namespacePath . DIRECTORY_SEPARATOR . 'hello.blade.php';

        $this->ensureDirectoryExists($namespacePath);
        (new Filesystem)->put($namespaceBadeFile, $text);

        return $text;
    }
}