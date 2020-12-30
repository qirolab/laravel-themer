<?php

namespace Qirolab\Theme\SolutionProviders;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\HasSolutionsForThrowable;
use Illuminate\Support\Facades\View;
use Qirolab\Theme\ThemeViewFinder;
use Throwable;

class ThemeViewNotFoundSolutionProvider implements HasSolutionsForThrowable
{
    /**
     * @return true
     */
    public function canSolve(Throwable $throwable): bool
    {
        return true;
    }

    /**
     * \Facade\IgnitionContracts\Solution[]
     *
     * @return array
     *
     * @psalm-return array{0?: mixed}
     */
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
     * @return string
     */
    public function getMessage(): string
    {
        $viewFinder = View::getFinder();
        $message = '';

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
