<?php

namespace Qirolab\Theme\SolutionProviders;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\HasSolutionsForThrowable;
use Illuminate\Support\Facades\View;
use Qirolab\Theme\ThemeViewFinder;
use Throwable;

class ThemeViewNotFoundSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        return true;
    }

    /** \Facade\IgnitionContracts\Solution[] */
    public function getSolutions(Throwable $throwable): array
    {
        $message = $this->getMessage();

        if ($message) {
            return [
                BaseSolution::create('Theme')
                    ->setSolutionDescription($message)
                    ->setDocumentationLinks([
                        'Documentation' => 'http://qirolab.com',
                    ]),
            ];
        }

        return [];
    }

    /**
     * Solution Message
     *
     * @return null|string
     */
    public function getMessage()
    {
        $viewFinder = View::getFinder();
        $message = null;

        if ($viewFinder instanceof ThemeViewFinder) {
            $activeTheme = $viewFinder->getActiveTheme();
            $parentTheme = $viewFinder->getParentTheme();

            if ($activeTheme) {
                $message = "**Active Theme:** `{$activeTheme}`";
            }

            if ($parentTheme) {
                $message .= "**Parent Theme:** `{$parentTheme}`";
            }
        }

        return $message;
    }
}
