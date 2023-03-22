<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\NonNullableIdInspection;

use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can make id non null', function (SmartFileInfo $smartFileInfo) {
    $inspection = $this->app->get(NonNullableIdInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/NonNullableIdInspectionTest'
));
