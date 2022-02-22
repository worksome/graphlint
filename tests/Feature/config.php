<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->load(
        'Olivernybroe\\Graphlint\\Inspections\\',
         '../../src/Inspections/*',
    )->public();
};