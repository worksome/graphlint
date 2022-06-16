<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;

use Worksome\Graphlint\Inspections\IgnoreNextLineSuppressorInspection;
use function Worksome\Graphlint\Tests\app;
use function Worksome\Graphlint\Tests\yieldFixtures;

it('can skip next line22', function (SmartFileInfo $smartFileInfo) {
    // Load in an inspection which should error, but will be skipped instead.
    $inspection = app()->get(CamelCaseFieldDefinitionInspection::class);
    $suppressor = app()->get(IgnoreNextLineSuppressorInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection, $suppressor);
})->with(yieldFixtures(
    __DIR__ . '/../../../test-resources/Inspections/IgnoreNextLineSuppressorInspectionTest'
));
