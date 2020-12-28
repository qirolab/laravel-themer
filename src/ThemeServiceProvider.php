<?php

namespace Qirolab\Theme;

use Facade\IgnitionContracts\SolutionProviderRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Qirolab\Theme\Commands\ThemeCreateCommand;
use Qirolab\Theme\SolutionProviders\ThemeViewNotFoundSolutionProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/theme.php' => config_path('theme.php'),
            ], 'config');

            $this->commands([
                ThemeCreateCommand::class,
            ]);
        }

        $this->registerThemeFinder();

        $this->registerSolutionProvider();
    }

    public function register()
    {
        $this->mergeConfig();
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
                ThemeViewNotFoundSolutionProvider::class
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

            if (config('theme.active')) {
                $themeFinder->setActiveTheme(config('theme.active'), config('theme.parent'));
            }

            return $themeFinder;
        });

        $this->app->make('view')->setFinder($this->app->make('theme.finder'));
    }
}
