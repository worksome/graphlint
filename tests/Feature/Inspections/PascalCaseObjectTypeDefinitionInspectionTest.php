<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\PascalCaseObjectTypeDefinitionInspection;

use function Worksome\Graphlint\Tests\app;

it('can rename type', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(PascalCaseObjectTypeDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/PascalCaseObjectTypeDefinitionInspectionTest',
    '*.graphql.inc',
));
