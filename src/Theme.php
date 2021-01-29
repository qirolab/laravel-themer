<?php

namespace Qirolab\Theme;

class Theme
{
    public static function finder()
    {
        return app('theme.finder');
    }

    public static function set(string $theme, string $parentTheme = null): void
    {
        self::finder()->setActiveTheme($theme, $parentTheme);
    }

    public static function clear(): void
    {
        self::finder()->clearThemes();
    }

    public static function active(): ?string
    {
        return self::finder()->getActiveTheme();
    }

    public static function parent(): ?string
    {
        return self::finder()->getParentTheme();
    }

    public static function viewPath(string $theme = null): ?string
    {
        $theme = $theme ?? self::active();

        if ($theme) {
            return self::finder()->getThemeViewPath($theme);
        }

        return null;
    }

    public static function path(string $path = null, string $theme = null): ?string
    {
        $theme = $theme ?? self::active();

        if ($theme) {
            return self::finder()->getThemePath($theme, $path);
        }

        return null;
    }

    public static function getViewPaths(): array
    {
        if (self::finder()) {
            return self::finder()->getViewFinder()->getPaths();
        }

        return app('view')->getFinder()->getPaths();
    }
}
