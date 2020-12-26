<?php

namespace Qirolab\Theme;

use Facade\IgnitionContracts\SolutionProviderRepository;
use Illuminate\Support\ServiceProvider;
use Qirolab\Theme\Commands\ThemeCreateCommand;
use Qirolab\Theme\SolutionProviders\ThemeViewNotFoundSolutionProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/theme.php' => config_path('theme.php'),
            ], 'config');

            $this->commands([
                ThemeCreateCommand::class,
            ]);
        }

        $this->app['view']->setFinder($this->app['theme.finder']);
    }

    public function register()
    {
        $this->mergeConfig();

        $this->registerThemeFinder();

        $this->registerSolutionProvider();
    }

    protected function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/theme.php', 'theme');
    }

    protected function registerSolutionProvider()
    {
        $solutionProvider = $this->app[SolutionProviderRepository::class];

        $solutionProvider->registerSolutionProvider(
            ThemeViewNotFoundSolutionProvider::class
        );
    }

    protected function registerThemeFinder()
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
    }
}