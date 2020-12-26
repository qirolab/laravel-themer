<?php

namespace Qirolab\Theme;

use Illuminate\View\FileViewFinder;

class ThemeViewFinder extends FileViewFinder
{
    protected $activeTheme;

    protected $parentTheme;

    public function setActiveTheme(string $theme = null, string $parentTheme = null)
    {
        $theme = $theme ?? config('theme.active');

        if ($theme) {
            $this->clearThemes();

            $this->activeTheme = $theme;
            array_unshift($this->paths, $this->getThemeViewPath($theme));

            if ($parentTheme) {
                $this->parentTheme = $parentTheme;
                array_unshift($this->paths, $this->getThemeViewPath($parentTheme));
            }
        }
    }

    /**
     * Get theme base path.
     *
     * @return null|string
     */
    public function getBasePath()
    {
        // TODO: throw error if base path is not set
        return config('theme.base_path');
    }

    /**
     * Get active theme name.
     *
     * @return null|string
     */
    public function getActiveTheme()
    {
        return $this->activeTheme;
    }

    /**
     * Get parent theme name.
     *
     * @return null|string
     */
    public function getParentTheme()
    {
        return $this->parentTheme;
    }

    public function getThemeViewPath(string $theme): string
    {
        return $this->resolvePath(
            $this->getBasePath() . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . 'views'
        );
    }

    public function clearThemes()
    {
        $paths = $this->paths;

        if ($this->getActiveTheme()) {
            if (($key = array_search($this->getThemeViewPath($this->getActiveTheme()), $paths)) !== false) {
                unset($paths[$key]);
            }
        }

        if ($this->getParentTheme()) {
            if (($key = array_search($this->getThemeViewPath($this->getParentTheme()), $paths)) !== false) {
                unset($paths[$key]);
            }
        }

        $this->activeTheme = null;
        $this->parentTheme = null;
        $this->setPaths($paths);
    }
}