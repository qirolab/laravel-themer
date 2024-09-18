<?php

namespace Qirolab\Theme\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Qirolab\Theme\Enums\CssFramework;
use Qirolab\Theme\Enums\JsFramework;
use Qirolab\Theme\Presets\Traits\AuthScaffolding;
use Qirolab\Theme\Presets\Traits\PackagesTrait;
use Qirolab\Theme\Presets\Traits\StubTrait;
use Qirolab\Theme\Presets\Vite\VitePresetExport;

class MakeThemeCommand extends Command
{
    use AuthScaffolding;
    use PackagesTrait;
    use StubTrait;

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
    public $themeName;

    /**
     * @var string
     */
    public $themeAuthor;

    /**
     * @var string
     */
    public $themeDescription;

    /**
     * @var string
     */
    public $themeVersion;

    /**
     * @var string
     */
    public $themeThumbnail;

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
        $this->askThemeMetadata();

        if (! $this->themeExists($this->themeName)) {
            $this->cssFramework = $this->askCssFramework();

            $this->jsFramework = $this->askJsFramework();

            $authScaffolding = $this->askAuthScaffolding();

            (new VitePresetExport(
                $this->themeName,
                $this->cssFramework,
                $this->jsFramework
            ))
            ->export();

            $this->exportAuthScaffolding($authScaffolding);
            $this->createFile($this->themePath('theme.json'), $this->themeJsonContent());

            $this->line("<options=bold>Theme Name:</options=bold> {$this->themeName}");
            $this->line("<options=bold>CSS Framework:</options=bold> {$this->cssFramework}");
            $this->line("<options=bold>JS Framework:</options=bold> {$this->jsFramework}");
            $this->line("<options=bold>Auth Scaffolding:</options=bold> {$authScaffolding}");
            $this->line('');

            $this->info("Theme scaffolding installed successfully.\n");

            $themePath = $this->relativeThemePath($this->themeName);
            $scriptDevCmd = '    "dev:'.$this->themeName.'": "vite --config '.$themePath.'/vite.config.js",';
            $scriptBuildCmd = '    "build:'.$this->themeName.'": "vite build --config '.$themePath.'/vite.config.js"';

            $this->comment('Add following line in the `<fg=blue>scripts</fg=blue>` section of the `<fg=blue>package.json</fg=blue>` file:');
            $this->line('');

            $this->line('"scripts": {', 'fg=magenta');
            $this->line('    ...', 'fg=magenta');
            $this->line('');
            $this->line($scriptDevCmd, 'fg=magenta');
            $this->line($scriptBuildCmd, 'fg=magenta');
            $this->line('}');

            $this->line('');
            $this->comment('And please run `<fg=blue>npm install && npm run dev:'.$this->themeName.'</fg=blue>` to compile your fresh scaffolding.');
        }
    }

    protected function themeJsonContent()
    {
        return json_encode([
            'name' => $this->themeName,
            'author' => $this->themeAuthor,
            'description' => $this->themeDescription ?? '',
            'version' => $this->themeVersion,
            'thumbnail' => $this->themeThumbnail,
        ], JSON_PRETTY_PRINT);
    }

    protected function askThemeMetadata()
    {
        $this->themeName = $this->argument('theme');

        if (! $this->themeName ) {
            $this->themeName = $this->askValid(
                'Name of your theme',
                'theme',
                ['required']
            );
        }

        $this->themeAuthor = $this->askValid(
            'Author of your theme',
            'author',
            ['required']
        );

        $this->themeDescription = $this->askValid(
            'Description of your theme (optional)',
            'description',
            ['nullable']
        );

        $this->themeThumbnail = $this->askValid(
            'Thumbnail of your theme (optional)',
            'thumbnail',
            ['nullable']
        );

        $this->themeVersion = "1.0.0";
    }

    protected function askCssFramework()
    {
        $options = [
            CssFramework::Bootstrap,
            CssFramework::Tailwind,
            'Skip',
        ];

        $cssFramework = $this->choice(
            'Select CSS Framework',
            $options,
            $_default = $options[0],
            $_maxAttempts = null,
            $_allowMultipleSelections = false
        );

        return $cssFramework;
    }

    protected function askJsFramework()
    {
        $options = [
            JsFramework::Vue3,
            JsFramework::React,
            'Skip',
        ];

        $jsFramework = $this->choice(
            'Select Javascript Framework',
            $options,
            $_default = $options[0], // Default value
            $_maxAttempts = null,
            $_allowMultipleSelections = false
        );

        return $jsFramework;
    }

    public function askAuthScaffolding()
    {
        $options = [
            'Views Only',
            'Controllers & Views',
            'Skip',
        ];

        $authScaffolding = $this->choice(
            'Publish Auth Scaffolding',
            $options,
            $_default = $options[0],
            $_maxAttempts = null,
            $_allowMultipleSelections = false
        );

        return $authScaffolding;
    }

    protected function themeExists(string $theme): bool
    {
        $directory = config('theme.base_path').DIRECTORY_SEPARATOR.$theme;

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
