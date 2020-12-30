<?php

namespace Qirolab\Theme\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
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
        $theme = $this->argument('theme');
        if (! $theme) {
            // $theme = $this->ask('What is the name of your theme?');

            $theme = $this->askValid(
                'What is the name of your theme?',
                'theme',
                ['required', 'min:3']
            );
        }

        $cssFramework = $this->choice(
            'Select CSS Framework?',
            ['Bootstrap', 'Tailwind CSS', 'Skip'],
            $default = 'Bootstrap',
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        $jsFramework = $this->choice(
            'Select Javascript Framework?',
            ['Vue', 'React', 'Skip'],
            $default = 'Vue',
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        $this->publishCSSFramework($cssFramework, $theme);
        $this->publishJSFramework($jsFramework, $theme);

        $this->info('Theme scaffolding installed successfully.');
        $this->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
    }

    protected function publishCSSFramework(string $cssFramework, string $theme): void
    {
        if ($cssFramework === 'Bootstrap') {
            (new Presets\Bootstrap($theme))->install();
        }

        if ($cssFramework === 'Tailwind CSS') {
            (new Presets\TailwindCSS($theme))->install();
        }
    }

    protected function publishJSFramework(string $jsFramework, string $theme): void
    {
        if ($jsFramework === 'Vue') {
            (new Presets\Vue($theme))->install();
        }

        if ($jsFramework === 'React') {
            (new Presets\React($theme))->install();
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
