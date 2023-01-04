<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Events;

use Worksome\Graphlint\Fixer\FixerResult;

class AfterFixerEvent implements EventInterface
{
    public function __construct(
        private readonly FixerResult $originalFixerResult,
        private readonly FixerResult $compiledFixerResult,
    ) {
    }

    public function getOriginalFixerResult(): FixerResult
    {
        return $this->originalFixerResult;
    }

    public function getCompiledFixerResult(): FixerResult
    {
        return $this->compiledFixerResult;
    }
}
