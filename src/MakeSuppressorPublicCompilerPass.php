<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Worksome\Graphlint\Contracts\SuppressorInspection;

/**
 * Before dependency injections can work for inspections, they need to be public.
 * This compiler pass makes sure to set them all as public.
 */
class MakeSuppressorPublicCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $definition) {
            if ($definition->getClass() === null) {
                continue;
            }

            if (! is_a($definition->getClass(), SuppressorInspection::class, true)) {
                continue;
            }

            $definition->setPublic(true);
        }
    }
}
