<?php

namespace Qirolab\Theme;

use Illuminate\Support\Facades\View;
use Qirolab\Theme\ThemeViewFinder;

class Theme
{
    public static function set(string $theme, string $parentTheme = null)
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            $viewFinder->setActiveTheme($theme, $parentTheme);
        }
    }

    public static function clear()
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            $viewFinder->clearThemes();
        }
    }

    public static function active()
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            return $viewFinder->getActiveTheme();
        }

        return null;
    }

    public static function parent()
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            return $viewFinder->getParentTheme();
        }

        return null;
    }

    public static function path(string $theme = null)
    {
        $theme = $theme ?? self::active();
        $viewFinder = View::getFinder();

        if ($theme && $viewFinder instanceof ThemeViewFinder) {
            return $viewFinder->getThemeViewPath($theme);
        }

        return null;
    }
}