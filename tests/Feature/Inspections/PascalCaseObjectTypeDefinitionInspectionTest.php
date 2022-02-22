<?php

namespace Olivernybroe\Graphlint\Tests\Unit\Inspections;

use Olivernybroe\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use Olivernybroe\Graphlint\Inspections\PascalCaseObjectTypeDefinitionInspection;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

it('can rename type', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(PascalCaseObjectTypeDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/PascalCaseObjectTypeDefinitionInspectionTest',
    '*.graphql.inc',
));