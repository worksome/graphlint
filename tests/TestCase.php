<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Worksome\Graphlint\Kernel;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public ContainerInterface $app;

    protected function setUp(): void
    {
        $kernel = new Kernel([
            __DIR__ . '/Feature/config.php',
        ]);

        $kernel->boot();

        $this->app = $kernel->getContainer();
    }
}
