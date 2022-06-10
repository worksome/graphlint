<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->load(
        'Worksome\\Graphlint\\Inspections\\',
        '../../src/Inspections/*',
    )->public();
};
