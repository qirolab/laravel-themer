<?php

namespace Qirolab\Theme;

use Illuminate\Support\Facades\View;

class Theme
{
    public static function set(string $theme, string $parentTheme = null)
    {
        $viewFinder = View::getFinder();

        if ($viewFinder instanceof ThemeViewFinder) {
            $viewFinder->setActiveTheme($theme, $parentTheme);
        }
    }
}