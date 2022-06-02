<?php

namespace Worksome\Graphlint\Events;

use Worksome\Graphlint\Analyser\AnalyserResult;

class AfterAnalyseEvent implements EventInterface
{
    public function __construct(
        private AnalyserResult $originalAnalyserResult,
        private AnalyserResult $compiledAnalyserResult,
    ) {}

    public function getOriginalAnalyserResult(): AnalyserResult
    {
        return $this->originalAnalyserResult;
    }

    public function getCompiledAnalyserResult(): AnalyserResult
    {
        return $this->compiledAnalyserResult;
    }
}