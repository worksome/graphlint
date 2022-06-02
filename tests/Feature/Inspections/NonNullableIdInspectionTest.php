<?php

namespace Worksome\Graphlint\Tests\Unit\Inspections;

use Worksome\Graphlint\Inspections\NonNullableIdInspection;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

it('can make id non null', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(NonNullableIdInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/NonNullableIdInspectionTest',
    '*.graphql.inc',
));