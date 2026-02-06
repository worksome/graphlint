<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\Configuration\Visitor;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $services->load(
        'Worksome\\Graphlint\\Inspections\\',
        '../../src/Inspections/*',
    )->public()->tag(Visitor::COMPILED);
};
