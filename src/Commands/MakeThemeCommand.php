<?php

namespace Qirolab\Theme\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Qirolab\Theme\Commands\Presets\AuthScaffolding;
use Qirolab\Theme\Commands\Presets\Bootstrap;
use Qirolab\Theme\Commands\Presets\React;
use Qirolab\Theme\Commands\Presets\TailwindCSS;
use Qirolab\Theme\Commands\Presets\Vue;
use Qirolab\Theme\Theme;
use Qirolab\Theme\Trails\HandleFiles;

class MakeThemeCommand extends Command
{
    use HandleFiles;

    /**
     * @var string
     */
    public $signature = 'make:theme {theme?}';

    /**
     * @var string
     */
    public $description = 'Create a new theme';

    public function handle(): void
    {
        $theme = $this->askTheme();

        if (! $this->themeExists($theme)) {
            $cssFramework = $this->askCssFramework();

            $jsFramework = $this->askJsFramework();

            $authScaffolding = $this->askAuthScaffolding();

            $this->line("<options=bold>Theme Name:</options=bold> {$theme}");
            $this->line("<options=bold>CSS Framework:</options=bold> {$cssFramework}");
            $this->line("<options=bold>JS Framework:</options=bold> {$jsFramework}");
            $this->line("<options=bold>Auth Scaffolding:</options=bold> {$authScaffolding}");
            $this->line('');

            $this->publishPresets($cssFramework, $jsFramework, $theme);
            $this->publishAuthScaffolding($authScaffolding, $theme, $cssFramework);

            $this->info("Theme scaffolding installed successfully.\n");

            $replaced = Str::replaceFirst(base_path(), '${__dirname}', 'require(`' . Theme::path('webpack.mix.js', $theme) . '`);');
            $this->comment('Add following line in your root "<fg=blue>webpack.mix.js</fg=blue>" file:');
            $this->line($replaced, 'fg=magenta');

            $this->line('');
            $this->comment('And please run "<fg=blue>npm install && npm run dev</fg=blue>" to compile your fresh scaffolding.');
        }
    }

    protected function askTheme()
    {
        $theme = $this->argument('theme');

        if (! $theme) {
            $theme = $this->askValid(
                'Name of your theme',
                'theme',
                ['required']
            );
        }

        return $theme;
    }

    protected function askCssFramework()
    {
        $cssFramework = $this->choice(
            'Select CSS Framework',
            ['Bootstrap', 'Tailwind', 'Skip'],
            $default = 'Bootstrap',
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        return $cssFramework;
    }

    protected function askJsFramework()
    {
        $jsFramework = $this->choice(
            'Select Javascript Framework',
            ['Vue', 'React', 'Skip'],
            $default = 'Vue',
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        return $jsFramework;
    }

    public function askAuthScaffolding()
    {
        $authScaffolding = $this->choice(
            'Publish Auth Scaffolding',
            ['Views Only', 'Controllers & Views', 'Skip'],
            $default = 'Views Only',
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        return $authScaffolding;
    }

    protected function themeExists(string $theme): bool
    {
        $directory = config('theme.base_path') . DIRECTORY_SEPARATOR . $theme;

        if (is_dir($directory)) {
            $this->error("`{$theme}` theme already exists.");

            return true;
        }

        return false;
    }

    protected function publishAuthScaffolding($authScaffolding, string $theme, string $preset): void
    {
        $scaffolding = new AuthScaffolding($theme, $preset);

        if ($authScaffolding == 'Controllers & Views') {
            $scaffolding->install();
        }

        if ($authScaffolding == 'Views Only') {
            $scaffolding->publishViews();
        }
    }

    protected function publishPresets(string $cssFramework, string $jsFramework, string $theme): void
    {
        if ($cssFramework === 'Bootstrap') {
            (new Bootstrap($theme))->install();

            $this->publishJSFramework($jsFramework, $theme);

            return;
        }

        if ($cssFramework === 'Tailwind') {
            $this->publishJSFramework($jsFramework, $theme);

            (new TailwindCSS($theme))->install();

            return;
        }

        $this->publishJSFramework($jsFramework, $theme);
    }

    protected function publishJSFramework(string $jsFramework, string $theme): void
    {
        if ($jsFramework === 'Vue') {
            (new Vue($theme))->install();
        }

        if ($jsFramework === 'React') {
            (new React($theme))->install();
        }
    }

    protected function askValid(string $question, string $field, array $rules)
    {
        $value = $this->ask($question);

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }

    protected function validateInput($rules, $fieldName, $value): ?string
    {
        $validator = Validator::make([
            $fieldName => $value,
        ], [
            $fieldName => $rules,
        ]);

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
    }
}
