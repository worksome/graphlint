<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/** @deprecated This should be removed as part of the auto-wire cleanup. */
final readonly class AutowireInterfacesCompilerPass implements CompilerPassInterface
{
    /**
     * @param string[] $typesToAutowire
     */
    public function __construct(
        private array $typesToAutowire,
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        $definitions = $container->getDefinitions();

        foreach ($definitions as $definition) {
            foreach ($this->typesToAutowire as $typeToAutowire) {
                if (! is_a((string) $definition->getClass(), $typeToAutowire, true)) {
                    continue;
                }

                $definition->setAutowired(true);

                continue 2;
            }
        }
    }
}
