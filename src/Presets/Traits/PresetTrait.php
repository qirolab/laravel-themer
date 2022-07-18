<?php

namespace Qirolab\Theme\Presets\Traits;

use Qirolab\Theme\Presets\Vite\VitePresetExport;
use Qirolab\Theme\Theme;

trait PresetTrait
{
    use HandleFiles;
    use PackagesTrait;

    /**
     * @var VitePresetExport
     */
    public $exporter;

    public function __construct(VitePresetExport $exporter)
    {
        $this->exporter = $exporter;
    }

    public function getTheme(): string
    {
        return $this->exporter->getTheme();
    }

    public function themePath($path = '')
    {
        return Theme::path($path, $this->getTheme());
    }

    public function jsPreset()
    {
        return $this->exporter->jsPreset();
    }
}
