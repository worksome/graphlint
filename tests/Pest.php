<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests;

use GraphQL\Language\Parser;
use GraphQL\Language\Printer;
use Illuminate\Support\Str;
use Psr\Container\ContainerInterface;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Analyser\Analyser;
use Worksome\Graphlint\Fixer\Fixer;
use Worksome\Graphlint\Inspections\Inspection;
use Worksome\Graphlint\Kernel;
use Worksome\Graphlint\Visitors\CompiledVisitorCollector;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/
uses()->beforeEach(function () {
    $kernel = new Kernel([
        __DIR__ . '/Feature/config.php',
    ]);
    $kernel->boot();

    $this->app = $kernel->getContainer();
})->in('Feature');
/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/
expect()->extend('toPassInspection', function (Inspection $inspection) {
    $smartFileInfo = $this->value;

    $inputAndExpected = StaticFixtureSplitter::splitFileInfoToInputAndExpected($smartFileInfo);

    $analyser = new Analyser();
    $result = $analyser->analyse(
        Parser::parse($inputAndExpected->getInput()),
        new CompiledVisitorCollector([
            $inspection
        ]),
    );

    if (Str::endsWith($smartFileInfo->getRealPath(), '.skip.graphql.inc')) {
        expect($result->getProblemsHolder()->getProblems())->toHaveCount(0);
        return;
    }

    expect($result->getProblemsHolder()->getProblems())->toHaveCount(1);

    /** @var Fixer $fixer */
    $fixer = app()->get(Fixer::class);
    $fixerResult = $fixer->fix($result);

    $schemaPrint = Printer::doPrint($fixerResult->getDocumentNode());
    expect($schemaPrint)->toEqual(
        $inputAndExpected->getExpected()
    );
});

expect()->extend('toHaveInspectionProblems', function (Inspection $inspection, int $count = 1) {
    $smartFileInfo = $this->value;

    $inputAndExpected = StaticFixtureSplitter::splitFileInfoToInputAndExpected($smartFileInfo);

    $analyser = new Analyser();
    $result = $analyser->analyse(
        Parser::parse($inputAndExpected->getInput()),
        new CompiledVisitorCollector([
            $inspection
        ]),
    );
    expect($result->getProblemsHolder()->getProblems())->toHaveCount($count);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/
function app(): ContainerInterface
{
    return test()->app;
}

/**
 * @return iterable<int, array<int, SmartFileInfo>>
 */
function yieldFixtures(string $directory): iterable
{
    /** @var iterable<int, array<int, SmartFileInfo>> $args */
    $args = StaticFixtureFinder::yieldDirectory(
        $directory,
        '*.graphql.inc',
    );
    foreach ($args as $files) {
        $file = $files[0];

        yield $file->getFilename() => $files;
    }
}
