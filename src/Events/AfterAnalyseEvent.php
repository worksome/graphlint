<?php

namespace Olivernybroe\Graphlint\Events;

use Olivernybroe\Graphlint\Analyser\AnalyserResult;

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