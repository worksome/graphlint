<?php

use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\Configuration\Visitor;
use Worksome\Graphlint\Visitors\CompiledVisitorCollector;
use Worksome\Graphlint\Visitors\OriginalVisitorCollector;

use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
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
        'Worksome\\Graphlint\\',
        '../src/*',
    )->exclude([
        '../src/Fixer/FixerResult.php',
        '../src/Visitors',
        '../src/Analyser/AnalyserResult.php',
        '../src/Result.php',
        '../src/ProblemsHolder.php',
        '../src/ProblemDescriptor.php',
        '../src/Kernel.php',
        '../src/Listeners/ConsolePrinterListener.php',
        '../src/Listeners/CheckstyleListener.php',
        '../src/Events',
        '../src/Laravel',
        '../src/InspectionDescription.php',
        '../src/Utils/ProblemOutputGenerator.php',
    ]);
};
