<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symfony\Component\Finder\SplFileInfo;
use Worksome\Graphlint\Inspections\NonNullableListInspection;
use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can make list non null', function (SplFileInfo $smartFileInfo) {
    $inspection = $this->app->get(NonNullableListInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(fn () => getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/NonNullableListInspectionTest'
));
