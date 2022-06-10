<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\PascalCaseObjectTypeDefinitionInspection;

use function Worksome\Graphlint\Tests\app;
use function Worksome\Graphlint\Tests\yieldFixtures;

it('can rename type', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(PascalCaseObjectTypeDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(yieldFixtures(
    __DIR__ . '/../../../test-resources/Inspections/PascalCaseObjectTypeDefinitionInspectionTest'
));
