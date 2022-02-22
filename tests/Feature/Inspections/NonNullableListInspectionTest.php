<?php

namespace Olivernybroe\Graphlint\Tests\Unit\Inspections;

use Olivernybroe\Graphlint\Inspections\NonNullableListInspection;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

it('can make list non null', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(NonNullableListInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/NonNullableListInspectionTest',
    '*.graphql.inc',
));