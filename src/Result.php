<?php

namespace Worksome\Graphlint;

use Worksome\Graphlint\Analyser\AnalyserResult;
use Worksome\Graphlint\Fixer\FixerResult;

class Result
{
    public function __construct(
        private AnalyserResult $analyserResult,
        private FixerResult $fixerResult,
    ) {}

    public function getAnalyserResult(): AnalyserResult
    {
        return $this->analyserResult;
    }

    public function getFixerResult(): FixerResult
    {
        return $this->fixerResult;
    }
}