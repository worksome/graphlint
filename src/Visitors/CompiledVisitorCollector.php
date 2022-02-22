<?php

namespace Olivernybroe\Graphlint\Visitors;

use Olivernybroe\Graphlint\Inspections\Inspection;

class CompiledVisitorCollector extends VisitorCollector
{
    /**
     * @param Inspection[] $inspections
     */
    public function __construct(
        private iterable $inspections,
    ) {}

    public function getInspections(): iterable
    {
        return $this->inspections;
    }
}