<?php

namespace Qirolab\Theme\Commands;

use Illuminate\Console\Command;

class ThemeCommand extends Command
{
    public $signature = 'laravel-theme';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
