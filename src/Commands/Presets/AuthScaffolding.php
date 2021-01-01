<?php

namespace Qirolab\Theme\Commands\Presets;

use Qirolab\Theme\Theme;
use Qirolab\Theme\Trails\HandleFiles;

class AuthScaffolding
{
    use HandleFiles;

    /**
     * @var string
     */
    protected $theme;

    /**
     * @var string
     */
    protected $themePath;

    /**
     * @var string
     */
    protected $preset;

    public function __construct(string $theme, string $preset)
    {
        $this->theme = $theme;

        $this->preset = $preset;

        $this->themePath = Theme::path('', $theme);

        $this->ensureDirectoryExists(Theme::path('views', $theme));
    }

    public function install(): void
    {
        $this->publishControllers()
            ->publishRequests()
            ->publishViews()
            ->publishTests()
            ->publishRoutes();
    }

    public function publishViews()
    {
        $this->ensureDirectoryExists(Theme::path('views/auth', $this->theme));
        $this->ensureDirectoryExists(Theme::path('views/layouts', $this->theme));

        $this->copyDirectory(
            __DIR__ . "/../../../stubs/App/resources/{$this->preset}/views/auth",
            Theme::path('views/auth', $this->theme)
        );
        $this->copyDirectory(
            __DIR__ . "/../../../stubs/App/resources/{$this->preset}/views/layouts",
            Theme::path('views/layouts', $this->theme)
        );

        copy(
            __DIR__ . "/../../../stubs/App/resources/{$this->preset}/views/home.blade.php",
            Theme::path('views/home.blade.php', $this->theme)
        );

        $this->replaceInFile('%theme%', $this->theme, Theme::path('views/layouts/app.blade.php', $this->theme));

        return $this;
    }

    protected function publishControllers()
    {
        $this->ensureDirectoryExists(app_path('Http/Controllers/Auth'));

        $this->copyDirectory(__DIR__ . '/../../../stubs/App/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        return $this;
    }

    protected function publishRequests()
    {
        $this->ensureDirectoryExists(app_path('Http/Requests/App'));

        $this->copyDirectory(__DIR__ . '/../../../stubs/App/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        return $this;
    }

    protected function publishTests()
    {
        $this->copyDirectory(__DIR__ . '/../../../stubs/App/tests/Feature', base_path('tests/Feature'));

        return $this;
    }

    protected function publishRoutes()
    {
        copy(__DIR__ . '/../../../stubs/App/routes/auth.php', base_path('routes/auth.php'));

        $webRoute = "

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');

require __DIR__.'/auth.php';";

        $this->append(
            base_path('routes/web.php'),
            $webRoute
        );

        return $this;
    }
}
