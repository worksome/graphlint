<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Visitors;

use Worksome\Graphlint\Inspections\Inspection;

class OriginalVisitorCollector extends VisitorCollector
{
    /**
     * @param Inspection[]|iterable $inspections
     */
    public function __construct(
        private readonly iterable $inspections,
    ) {
    }

    public function getInspections(): iterable
    {
        return $this->inspections;
    }
}
