<?php

namespace Qirolab\Theme;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Qirolab\Theme\Theme
 */
class ThemeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-theme';
    }
}
