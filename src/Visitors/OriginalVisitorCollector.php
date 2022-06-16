<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Visitors;

use Worksome\Graphlint\Contracts\SuppressorInspection;
use Worksome\Graphlint\Inspections\Inspection;

class OriginalVisitorCollector extends VisitorCollector
{
    /**
     * @param iterable<Inspection> $inspections
     * @param SuppressorInspection[] $suppressors
     */
    public function __construct(
        private readonly iterable $inspections,
        private readonly array $suppressors,
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
