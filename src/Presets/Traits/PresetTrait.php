<?php

namespace Qirolab\Theme\Presets\Traits;

use Qirolab\Theme\Presets\PresetExport;
use Qirolab\Theme\Theme;

trait PresetTrait
{
    use HandleFiles;
    use PackagesTrait;

    /**
     * @var PresetExport
     */
    public $exporter;

    public function __construct(PresetExport $exporter)
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
