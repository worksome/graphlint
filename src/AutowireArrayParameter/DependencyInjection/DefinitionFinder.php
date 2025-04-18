<?php

declare(strict_types=1);

namespace Worksome\Graphlint\AutowireArrayParameter\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Throwable;
use Worksome\Graphlint\AutowireArrayParameter\Exception\DependencyInjection\DefinitionForTypeNotFoundException;

/**
 * @api
 */
final class DefinitionFinder
{
    /**
     * @return Definition[]
     */
    public function findAllByType(ContainerBuilder $containerBuilder, string $type): array
    {
        $definitions = [];
        $containerBuilderDefinitions = $containerBuilder->getDefinitions();
        foreach ($containerBuilderDefinitions as $name => $definition) {
            $class = $definition->getClass() ?: $name;
            if (! $this->doesClassExists($class)) {
                continue;
            }

            if (is_a($class, $type, true)) {
                $definitions[$name] = $definition;
            }
        }

        return $definitions;
    }

    public function getByType(ContainerBuilder $containerBuilder, string $type): Definition
    {
        $definition = $this->getByTypeIfExists($containerBuilder, $type);
        if ($definition !== null) {
            return $definition;
        }

        throw new DefinitionForTypeNotFoundException(sprintf('Definition for type "%s" was not found.', $type));
    }

    private function getByTypeIfExists(ContainerBuilder $containerBuilder, string $type): Definition|null
    {
        $containerBuilderDefinitions = $containerBuilder->getDefinitions();
        foreach ($containerBuilderDefinitions as $name => $definition) {
            $class = $definition->getClass() ?: $name;
            if (! $this->doesClassExists($class)) {
                continue;
            }

            if (is_a($class, $type, true)) {
                return $definition;
            }
        }

        return null;
    }

    private function doesClassExists(string $class): bool
    {
        try {
            return class_exists($class);
        } catch (Throwable) {
            return false;
        }
    }
}
