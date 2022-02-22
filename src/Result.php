<?php

namespace Olivernybroe\Graphlint;

use Olivernybroe\Graphlint\Analyser\AnalyserResult;
use Olivernybroe\Graphlint\Fixer\FixerResult;

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