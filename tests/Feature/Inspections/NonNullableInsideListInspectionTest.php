<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\NonNullableInsideListInspection;

use function Worksome\Graphlint\Tests\app;

it('can make type inside list non null', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(NonNullableInsideListInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/NonNullableInsideListInspectionTest',
    '*.graphql.inc',
));
