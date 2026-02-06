<?php

declare(strict_types=1);

use SebastianBergmann\Diff\Differ;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\Configuration\Visitor;
use Worksome\Graphlint\Contracts\SuppressorInspection;
use Worksome\Graphlint\Diff\ColorConsoleDiffFormatter;
use Worksome\Graphlint\Diff\ConsoleDiffer;
use Worksome\Graphlint\Diff\DifferFactory;
use Worksome\Graphlint\Fixer\Fixer;
use Worksome\Graphlint\Graphlint;
use Worksome\Graphlint\Inspections\IgnoreNextLineSuppressorInspection;
use Worksome\Graphlint\Listeners\GraphlintListener;
use Worksome\Graphlint\PostFixes\PostFixer;
use Worksome\Graphlint\Visitors\CompiledVisitorCollector;
use Worksome\Graphlint\Visitors\OriginalVisitorCollector;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    // Register interface/class tags for autoconfiguration
    $services->instanceof(SuppressorInspection::class)
        ->tag('graphlint.suppressor');

    $services->instanceof(GraphlintListener::class)
        ->tag('graphlint.listener');

    $services->instanceof(PostFixer::class)
        ->tag('graphlint.post_fixer');

    // Diff
    $services->set(ColorConsoleDiffFormatter::class);
    $services->set(ConsoleDiffer::class)->public();
    $services->set(DifferFactory::class);
    $services->set(Differ::class)
        ->factory([service(DifferFactory::class), 'create']);

    // Load all services from src
    $services->load(
        'Worksome\\Graphlint\\',
        '../src/*',
    )->exclude([
        '../src/AutowireArrayParameter',
        '../src/Diff',
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

    // Fixer with tagged post fixers
    $services->set(Fixer::class)
        ->public()
        ->args([
            '$postFixers' => tagged_iterator('graphlint.post_fixer'),
        ]);

    // Visitor collectors with tagged iterators
    $services->set(OriginalVisitorCollector::class)
        ->args([
            '$inspections' => tagged_iterator(Visitor::ORIGINAL),
            '$suppressors' => tagged_iterator('graphlint.suppressor'),
        ]);

    $services->set(CompiledVisitorCollector::class)
        ->args([
            '$inspections' => tagged_iterator(Visitor::COMPILED),
            '$suppressors' => tagged_iterator('graphlint.suppressor'),
        ]);

    // Register suppressor inspections explicitly
    $services->set(IgnoreNextLineSuppressorInspection::class)
        ->tag('graphlint.suppressor');

    // Graphlint main class with listeners (added dynamically)
    $services->set(Graphlint::class)
        ->public()
        ->args([
            '$listeners' => tagged_iterator('graphlint.listener'),
        ]);
};
