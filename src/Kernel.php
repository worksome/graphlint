<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    /**
     * @param non-empty-string[] $configFiles
     */
    public function __construct(
        private readonly array $configFiles,
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

        foreach ($this->configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MakeInspectionsPublicCompilerPass());
        $container->addCompilerPass(new MakeSuppressorPublicCompilerPass());
    }

    public function getCacheDir(): string
    {
        return sprintf(
            '%s/.graphlint/cache/%s-%s',
            sys_get_temp_dir(),
            basename(\Safe\getcwd()),
            $this->environment
        );
    }

    public function getLogDir(): string
    {
        return sprintf(
            '%s/.graphlint/logs/%s-%s',
            sys_get_temp_dir(),
            basename(\Safe\getcwd()),
            $this->environment
        );
    }
}
