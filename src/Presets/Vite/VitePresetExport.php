<?php

namespace Qirolab\Theme\Presets\Vite;

use Qirolab\Theme\Presets\Traits\HandleFiles;
use Qirolab\Theme\Presets\Traits\StubTrait;
use Qirolab\Theme\Theme;

class VitePresetExport
{
    use HandleFiles;
    use StubTrait;

    /**
     * @var string
     */
    protected $theme;

    /**
     * @var string
     */
    public $cssFramework;

    /**
     * @var string
     */
    public $jsFramework;

    public function __construct(string $theme, string $cssFramework, string $jsFramework)
    {
        $this->theme = $theme;
        $this->cssFramework = $cssFramework;
        $this->jsFramework = $jsFramework;

        $this->ensureDirectoryExists(Theme::path('', $theme));
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function export(): void
    {
        if ($this->cssPreset()) {
            $this->cssPreset()->export();
        }

        if ($this->jsPreset()) {
            $this->jsPreset()->export();
        }

        $this->exportViteConfig();
    }

    public function getPreset($preset)
    {
        $preset = str_replace(' ', '', $preset);

        $presetClass = "\\Qirolab\\Theme\\Presets\\Vite\\{$preset}Preset";

        if (class_exists($presetClass)) {
            return new $presetClass($this);
        }
    }

    public function cssPreset()
    {
        return $this->getPreset($this->cssFramework);
    }

    /**
     * @return null|object
     */
    public function jsPreset()
    {
        return $this->getPreset($this->jsFramework);
    }

    public function exportViteConfig()
    {
        $placeHolders = [
            '%app_css_input%',
            '%theme_path%',
            '%theme_name%',
            '%css_config%',
            '%tailwind_import%',
            '%vue_import%',
            '%vue_plugin_config%',
            '%react_import%',
            '%react_plugin_config%',
            '%bootstrap%',
        ];

        $themePath = $this->relativeThemePath($this->theme);

        $configData = file_get_contents($this->stubPath('vite.config.js'));
        $configData = str_replace('%theme_path%', $themePath.DIRECTORY_SEPARATOR, $configData);
        $configData = str_replace('%theme_name%', $this->theme, $configData);

        if ($this->cssPreset()) {
            $configData = $this->cssPreset()->updateViteConfig($configData);
        }

        if ($this->jsPreset()) {
            $configData = $this->jsPreset()->updateViteConfig($configData);
        }

        foreach ($placeHolders as $placeHolder) {
            $configData = str_replace($placeHolder, '', $configData);
        }

        $this->createFile(Theme::path('vite.config.js', $this->theme), $configData);
    }
}
