<?php

declare(strict_types=1);

use SebastianBergmann\Diff\Differ;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\Configuration\Visitor;
use Worksome\Graphlint\Diff\ColorConsoleDiffFormatter;
use Worksome\Graphlint\Diff\ConsoleDiffer;
use Worksome\Graphlint\Diff\DifferFactory;
use Worksome\Graphlint\Visitors\CompiledVisitorCollector;
use Worksome\Graphlint\Visitors\OriginalVisitorCollector;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->public()->set(Application::class);

    // Collectors
    $services->set(OriginalVisitorCollector::class)
        ->args([tagged_iterator(Visitor::ORIGINAL)]);
    $services->set(CompiledVisitorCollector::class)
        ->args([tagged_iterator(Visitor::COMPILED)]);

    // Diff
    $services->set(ColorConsoleDiffFormatter::class);
    $services->set(ConsoleDiffer::class);
    $services->set(DifferFactory::class);
    $services->set(Differ::class)
        ->factory([service(DifferFactory::class), 'create']);

    // Internal
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
        '../src/Utils/AutowireInterfacesCompilerPass.php',
        '../src/Utils/ProblemOutputGenerator.php',
    ]);
};
