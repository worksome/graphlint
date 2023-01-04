<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use Worksome\Graphlint\Analyser\AnalyserResult;
use Worksome\Graphlint\Fixer\FixerResult;

class Result
{
    public function __construct(
        private readonly AnalyserResult $analyserResult,
        private readonly FixerResult $fixerResult,
    ) {
    }

    public function getAnalyserResult(): AnalyserResult
    {
        return $this->analyserResult;
    }

    public function getFixerResult(): FixerResult
    {
        return $this->fixerResult;
    }
}
