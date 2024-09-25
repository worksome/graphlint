<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symfony\Component\Finder\SplFileInfo;
use Worksome\Graphlint\Inspections\NonNullableInsideListInspection;
use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can make type inside list non null', function (SplFileInfo $smartFileInfo) {
    $inspection = $this->app->get(NonNullableInsideListInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(fn () => getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/NonNullableInsideListInspectionTest'
));
