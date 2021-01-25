<?php

namespace Qirolab\Theme\Presets\Traits;

use Qirolab\Theme\Theme;

trait AuthScaffolding
{
    use HandleFiles;

    public function themePath($path = '')
    {
        return Theme::path($path, $this->theme);
    }

    public function exportAuthScaffolding(string $authScaffolding = 'Views Only'): void
    {
        if ($authScaffolding == 'Controllers & Views') {
            $this->exportControllers()
                ->exportRequests()
                ->exportViews()
                ->exportRoutes()
                ->exportTests();
        }

        if ($authScaffolding == 'Views Only') {
            $this->exportViews();
        }
    }

    public function exportControllers() :self
    {
        $this->ensureDirectoryExists(app_path('Http/Controllers/Auth'));

        $controllers = [
            'Http/Controllers/Auth/AuthenticatedSessionController.php',
            'Http/Controllers/Auth/ConfirmablePasswordController.php',
            'Http/Controllers/Auth/EmailVerificationNotificationController.php',
            'Http/Controllers/Auth/EmailVerificationPromptController.php',
            'Http/Controllers/Auth/NewPasswordController.php',
            'Http/Controllers/Auth/PasswordResetLinkController.php',
            'Http/Controllers/Auth/RegisteredUserController.php',
            'Http/Controllers/Auth/VerifyEmailController.php',
        ];

        foreach ($controllers as $controller) {
            $controllerPath = app_path($controller);

            $overwrite = false;

            if (file_exists($controllerPath)) {
                $overwrite = $this->confirm(
                    "<fg=red>{$controller} already exists.</fg=red>\n " .
                    'Do you want to overwrite?',
                    false
                );
            }

            if (! file_exists($controllerPath) || $overwrite) {
                copy(
                    __DIR__ . '/../../../stubs/App/' . $controller,
                    $controllerPath
                );
            }
        }

        return $this;
    }

    protected function exportRequests(): self
    {
        $this->ensureDirectoryExists(app_path('Http/Requests/Auth'));

        $loginRequest = app_path('Http/Requests/Auth/LoginRequest.php');

        $overwrite = false;

        if (file_exists($loginRequest)) {
            $overwrite = $this->confirm(
                "<fg=red>app/Http/Requests/Auth/LoginRequest.php already exists.</fg=red>\n " .
                    'Do you want to overwrite?',
                false
            );
        }

        if (! file_exists($loginRequest) || $overwrite) {
            copy(
                __DIR__ . '/../../../stubs/App/Http/Requests/Auth/LoginRequest.php',
                $loginRequest
            );
        }

        return $this;
    }

    public function exportViews(): self
    {
        $this->ensureDirectoryExists(Theme::path('views/auth', $this->theme));
        $this->ensureDirectoryExists(Theme::path('views/layouts', $this->theme));

        if (is_dir(__DIR__ . "/../../../stubs/App/resources/{$this->cssFramework}/views")) {
            $this->copyDirectory(
                __DIR__ . "/../../../stubs/App/resources/{$this->cssFramework}/views/auth",
                Theme::path('views/auth', $this->theme)
            );
            $this->copyDirectory(
                __DIR__ . "/../../../stubs/App/resources/{$this->cssFramework}/views/layouts",
                Theme::path('views/layouts', $this->theme)
            );

            copy(
                __DIR__ . "/../../../stubs/App/resources/{$this->cssFramework}/views/home.blade.php",
                Theme::path('views/home.blade.php', $this->theme)
            );

            $this->replaceInFile('%theme%', $this->theme, Theme::path('views/layouts/app.blade.php', $this->theme));
        }

        return $this;
    }

    public function exportRoutes(): self
    {
        $routeFile = 'routes/auth.php';

        $overwrite = false;

        if (file_exists(base_path($routeFile))) {
            $overwrite = $this->confirm(
                "<fg=red>{$routeFile} already exists.</fg=red>\n " .
                    'Do you want to overwrite?',
                false
            );
        }

        if (! file_exists(base_path($routeFile)) || $overwrite) {
            copy(__DIR__ . '/../../../stubs/App/routes/auth.php', base_path('routes/auth.php'));

            $homeRoute = "

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');

";
            $requireAuth = "require __DIR__.'/auth.php';";

            if (! exec('grep ' . escapeshellarg($requireAuth) . ' ' . base_path('routes/web.php'))) {
                $this->append(
                    base_path('routes/web.php'),
                    $homeRoute . $requireAuth
                );
            }
        }

        return $this;
    }

    public function exportTests(): self
    {
        $this->ensureDirectoryExists(base_path('tests/Feature'));

        $testFiles = [
            'tests/Feature/AuthenticationTest.php',
            'tests/Feature/EmailVerificationTest.php',
            'tests/Feature/PasswordConfirmationTest.php',
            'tests/Feature/PasswordResetTest.php',
            'tests/Feature/RegistrationTest.php',
        ];

        foreach ($testFiles as $testFile) {
            $testFilePath = base_path($testFile);

            $overwrite = false;

            if (file_exists($testFilePath)) {
                $overwrite = $this->confirm(
                    "<fg=red>{$testFile} already exists.</fg=red>\n " .
                    'Do you want to overwrite?',
                    false
                );
            }

            if (! file_exists($testFilePath) || $overwrite) {
                copy(
                    __DIR__ . '/../../../stubs/App/' . $testFile,
                    $testFilePath
                );
            }
        }

        return $this;
    }
}
