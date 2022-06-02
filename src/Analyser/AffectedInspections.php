<?php

namespace Worksome\Graphlint\Analyser;

use Illuminate\Support\Collection;
use Worksome\Graphlint\Inspections\Inspection;

class AffectedInspections
{
    /**
     * @var Collection<Inspection>
     */
    private Collection $inspections;

    public function __construct()
    {
        $this->inspections = new Collection();
    }

    /**
     * @return Inspection[]
     */
    public function getInspections(): array
    {
        return $this->inspections->all();
    }

    public function isEmpty(): bool
    {
        return $this->inspections->isEmpty();
    }

    public function addInspection(Inspection $inspection): self
    {
        if ($this->inspections->contains($inspection)) {
            return $this;
        }

        $this->inspections->add($inspection);
        return $this;
    }
}