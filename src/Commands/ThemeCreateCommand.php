<?php

namespace Qirolab\Theme\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ThemeCreateCommand extends Command
{
    /**
     * @var string
     */
    public $signature = 'theme:create {theme}';

    /**
     * @var string
     */
    public $description = 'Create your theme.';

    public function handle()
    {
        $basePath = config('theme.base_path');
        $theme = $this->argument('theme');

        if ($basePath && $theme) {
            $themePath = $basePath . DIRECTORY_SEPARATOR . $theme;

            (new Filesystem)->ensureDirectoryExists($themePath);

            (new Filesystem)->copyDirectory(__DIR__ . '/../../resources', $themePath);

            $this->replaceInFile(
                '::theme::',
                $theme,
                $themePath . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'app.blade.php'
            );
        }

        $this->info("`{$theme}` theme has been created.");
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
