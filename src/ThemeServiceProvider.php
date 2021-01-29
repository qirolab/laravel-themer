<?php

namespace Qirolab\Theme;

use Facade\IgnitionContracts\SolutionProviderRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Qirolab\Theme\Commands\MakeThemeCommand;
use Qirolab\Theme\SolutionProviders\ThemeSolutionProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/theme.php' => config_path('theme.php'),
            ], 'config');

            $this->commands([
                MakeThemeCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfig();

        $this->registerThemeFinder();

        $this->registerSolutionProvider();
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/theme.php', 'theme');
    }

    protected function registerSolutionProvider(): void
    {
        try {
            $solutionProvider = $this->app->make(SolutionProviderRepository::class);

            $solutionProvider->registerSolutionProvider(
                ThemeSolutionProvider::class
            );
        } catch (BindingResolutionException $error) {
        }
    }

    protected function registerThemeFinder(): void
    {
        $this->app->singleton('theme.finder', function ($app) {
            $themeFinder = new ThemeViewFinder(
                $app['files'],
                $app['config']['view.paths']
            );

            $themeFinder->setHints(
                $this->app->make('view')->getFinder()->getHints()
            );

            return $themeFinder;
        });

        if (config('theme.active')) {
            $this->app->make('theme.finder')->setActiveTheme(config('theme.active'), config('theme.parent'));
        }

        // If need to replace Laravel's view finder with package's theme.finder
        // $this->app->make('view')->setFinder($this->app->make('theme.finder'));
    }
}
