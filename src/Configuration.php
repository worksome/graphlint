<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use Worksome\Graphlint\Inspections\Inspection;

class Configuration
{
    /**
     * @param Inspection[] $inspections
     */
    public function __construct(
        private array $inspections,
    ) {
    }

    /**
     * @return Inspection[]
     */
    public function getInspections(): array
    {
        return $this->inspections;
    }
}
