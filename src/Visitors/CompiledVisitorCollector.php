<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Visitors;

use Worksome\Graphlint\Contracts\SuppressorInspection;
use Worksome\Graphlint\Inspections\Inspection;

class CompiledVisitorCollector extends VisitorCollector
{
    /**
     * @param iterable<Inspection>           $inspections
     * @param iterable<SuppressorInspection> $suppressors
     */
    public function __construct(
        private readonly iterable $inspections,
        private readonly iterable $suppressors,
    ) {
    }

    public function getInspections(): iterable
    {
        return $this->inspections;
    }

    public function getSuppressors(): iterable
    {
        return $this->suppressors;
    }
}
