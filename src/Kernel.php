<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;
use Symplify\PackageBuilder\ValueObject\ConsoleColorDiffConfig;
use Worksome\Graphlint\Inspections\Inspection;
use Worksome\Graphlint\PostFixes\PostFixer;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    /**
     * @param non-empty-string[] $configFiles
     */
    public function __construct(
        private array $configFiles,
    ) {
        parent::__construct(
            'local',
            true,
        );
    }

    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): array
    {
        return [
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../config/services.php');
        $loader->load(ConsoleColorDiffConfig::FILE_PATH);

        foreach ($this->configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AutowireArrayParameterCompilerPass());
        $container->addCompilerPass(new AutowireInterfacesCompilerPass([
            Inspection::class,
            PostFixer::class,
        ]));
        $container->addCompilerPass(new MakeInspectionsPublicCompilerPass());
    }
}
