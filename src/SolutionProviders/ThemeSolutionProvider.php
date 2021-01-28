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

        if ($message) {
            return [
                BaseSolution::create('Theme')
                    ->setSolutionDescription($message)
                    ->setDocumentationLinks([
                        'Documentation' => 'https://qirolab.com/posts/laravel-themer-multi-theme-support-for-laravel-application-1609688215',
                    ]),
            ];
        }

        return [];
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
