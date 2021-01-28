<?php

namespace Qirolab\Theme\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Qirolab\Theme\Presets\PresetExport;
use Qirolab\Theme\Presets\Traits\AuthScaffolding;
use Qirolab\Theme\Presets\Traits\PackagesTrait;
use Qirolab\Theme\Theme;

class MakeThemeCommand extends Command
{
    use AuthScaffolding;
    use PackagesTrait;

    /**
     * @var string
     */
    public $signature = 'make:theme {theme?}';

    /**
     * @var string
     */
    public $description = 'Create a new theme';

    /**
     * @var string
     */
    public $theme;

    /**
     * @var string
     */
    public $themePath;

    /**
     * @var string
     */
    public $cssFramework;

    /**
     * @var string
     */
    public $jsFramework;

    public function handle(): void
    {
        $this->theme = $this->askTheme();

        if (! $this->themeExists($this->theme)) {
            $this->cssFramework = $this->askCssFramework();

            $this->jsFramework = $this->askJsFramework();

            $authScaffolding = $this->askAuthScaffolding();

            (new PresetExport(
                $this->theme,
                $this->cssFramework,
                $this->jsFramework
            ))
            ->export();

            $this->exportAuthScaffolding($authScaffolding);

            $this->line("<options=bold>Theme Name:</options=bold> {$this->theme}");
            $this->line("<options=bold>CSS Framework:</options=bold> {$this->cssFramework}");
            $this->line("<options=bold>JS Framework:</options=bold> {$this->jsFramework}");
            $this->line("<options=bold>Auth Scaffolding:</options=bold> {$authScaffolding}");
            $this->line('');

            $this->info("Theme scaffolding installed successfully.\n");

            $replaced = Str::replaceFirst(base_path(), '${__dirname}', 'require(`' . Theme::path('webpack.mix.js', $this->theme) . '`);');
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
        $jsFrameworks = $this->getAllowedJsFrameworks();

        $jsFramework = $this->choice(
            'Select Javascript Framework',
            $jsFrameworks,
            $jsFrameworks[0], // Default value
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        return $jsFramework;
    }

    public function getAllowedJsFrameworks() :array
    {
        $vueVersion = $this->getVueVersion($dev = true) ?? $this->getVueVersion($dev = false) ;

        if ($vueVersion && $this->versionLessThan($vueVersion, '3.0.0')) {
            return ['Vue 2', 'React', 'Skip'];
        }

        if ($vueVersion && $this->versionGreaterOrEqual($vueVersion, '3.0.0')) {
            return ['Vue 3', 'React', 'Skip'];
        }

        return ['Vue 2', 'Vue 3', 'React', 'Skip'];
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
