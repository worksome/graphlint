<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Configuration;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\GraphlintSet;
use Worksome\Graphlint\Inspections\IgnoreByNameSuppressorInspection;
use Worksome\Graphlint\Inspections\Inspection;

final class GraphlintConfigBuilder
{
    /** @var array<GraphlintSet> */
    private array $sets = [];

    /** @var array<class-string<Inspection>> */
    private array $inspections = [];

    /** @var array<string> */
    private array $ignored = [];

    public function __invoke(ContainerConfigurator $config): void
    {
        $services = $config->services();

        foreach ($this->sets as $set) {
            $config->import($set->value);
        }

        foreach ($this->inspections as $inspection) {
            $services->set($inspection)->tag(Visitor::COMPILED);
        }

        $services
            ->set(IgnoreByNameSuppressorInspection::class)
            ->call('configure', $this->ignored);
    }

    public function withPreparedSets(
        bool $standard = false,
    ): self {
        if ($standard) {
            $this->sets[] = GraphlintSet::Standard;
        }

        return $this;
    }

    /** @param array<class-string<Inspection>> $inspections */
    public function withInspections(array $inspections): self
    {
        $this->inspections = [...$this->inspections, ...$inspections];

        return $this;
    }

    /** @param array<non-empty-string> $ignored */
    public function ignoring(array $ignored): self
    {
        $this->ignored = [...$this->ignored, ...$ignored];

        return $this;
    }
}
