<?php

namespace Qirolab\Theme;

use Illuminate\Support\Facades\View;

class Theme
{
    public static function set(string $theme, string $parentTheme = null): void
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            $viewFinder->setActiveTheme($theme, $parentTheme);
        }
    }

    public static function clear(): void
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            $viewFinder->clearThemes();
        }
    }

    public static function active(): ?string
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            return $viewFinder->getActiveTheme();
        }

        return null;
    }

    public static function parent(): ?string
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            return $viewFinder->getParentTheme();
        }

        return null;
    }

    public static function path(string $theme = null): ?string
    {
        $theme = $theme ?? self::active();
        $viewFinder = View::getFinder();

        if ($theme && $viewFinder instanceof ThemeViewFinder) {
            return $viewFinder->getThemeViewPath($theme);
        }

        return null;
    }
}
