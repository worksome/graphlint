<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Events;

use Worksome\Graphlint\Analyser\AnalyserResult;

class AfterAnalyseEvent implements EventInterface
{
    public function __construct(
        private readonly AnalyserResult $originalAnalyserResult,
        private readonly AnalyserResult $compiledAnalyserResult,
    ) {
    }

    public function getOriginalAnalyserResult(): AnalyserResult
    {
        return $this->originalAnalyserResult;
    }

    public function getCompiledAnalyserResult(): AnalyserResult
    {
        return $this->compiledAnalyserResult;
    }
}
