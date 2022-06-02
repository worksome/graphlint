<?php

namespace Worksome\Graphlint;

use Worksome\Graphlint\Inspections\Inspection;
use Worksome\Graphlint\PostFixes\PostFixer;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    public function __construct(
        private array $configFiles,
    ) {
        parent::__construct(
            'local',
            true,
        );
    }

    public function registerBundles(): array
    {
        return [
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/../config/services.php');

        foreach ($this->configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AutowireArrayParameterCompilerPass());
        $container->addCompilerPass(new AutowireInterfacesCompilerPass([
            Inspection::class,
            PostFixer::class,
        ]));
    }
}