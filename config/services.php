<?php

use Olivernybroe\Graphlint\Configuration\Visitor;
use Olivernybroe\Graphlint\Visitors\CompiledVisitorCollector;
use Olivernybroe\Graphlint\Visitors\OriginalVisitorCollector;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $services->public()->set(Application::class);

    $services->set(OriginalVisitorCollector::class)
        ->args([tagged_iterator(Visitor::ORIGINAL)]);
    $services->set(CompiledVisitorCollector::class)
        ->args([tagged_iterator(Visitor::COMPILED)]);

    $services->load(
        'Olivernybroe\\Graphlint\\',
        '../src/*',
    )->exclude([
        '../src/Fixer/FixerResult.php',
        '../src/Visitors',
        '../src/Inspections',
        '../src/Analyser/AnalyserResult.php',
        '../src/Result.php',
        '../src/ProblemsHolder.php',
        '../src/ProblemDescriptor.php',
        '../src/Kernel.php',
        '../src/ConsolePrinterListener.php',
        '../src/Events',
        '../src/Laravel',
    ]);
};