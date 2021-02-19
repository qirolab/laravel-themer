<?php

namespace Qirolab\Theme\SolutionProviders;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\HasSolutionsForThrowable;
use Qirolab\Theme\Theme;
use Throwable;

class ThemeSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        return true;
    }

    public function getSolutions(Throwable $throwable): array
    {
        $message = $this->getMessage();

        if (app()->runningInConsole() || ! $message) {
            return [];
        }

        return [
            BaseSolution::create('Theme')
                ->setSolutionDescription($message)
                ->setDocumentationLinks([
                    'Documentation' => 'https://github.com/qirolab/laravel-themer',
                    'Video Tutorial' => 'https://www.youtube.com/watch?v=Ty4ZwFTLYXE',
                ]),
        ];
    }

    public function getMessage(): string
    {
        $message = '';

        $activeTheme = Theme::active();
        $parentTheme = Theme::parent();

        if ($activeTheme) {
            $message = "**Active Theme:** `{$activeTheme}`  ";
        }

        if ($parentTheme) {
            $message .= "**Parent Theme:** `{$parentTheme}`";
        }

        return $message;
    }
}
