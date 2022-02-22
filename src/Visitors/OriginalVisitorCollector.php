<?php

namespace Olivernybroe\Graphlint\Visitors;

use Olivernybroe\Graphlint\Inspections\Inspection;

class OriginalVisitorCollector extends VisitorCollector
{
    /**
     * @param Inspection[]|iterable $inspections
     */
    public function __construct(
        private iterable $inspections,
    ) {}

    public function getInspections(): iterable
    {
        return $this->inspections;
    }
}