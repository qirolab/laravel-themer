<?php

namespace Qirolab\Theme\Middleware;

use Closure;
use Illuminate\Http\Request;
use Qirolab\Theme\Theme;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $theme, string $parentTheme = null)
    {
        Theme::set($theme, $parentTheme);

        return $next($request);
    }
}
