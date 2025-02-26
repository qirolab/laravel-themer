<?php

if (! function_exists('theme')) {
    /**
     * Get the Theme instance.
     *
     * @return \Qirolab\Theme\Theme
     */
    function theme()
    {
        return app('theme');
    }
}