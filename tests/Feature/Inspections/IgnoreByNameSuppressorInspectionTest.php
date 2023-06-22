<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\IgnoreByNameSuppressorInspection;

use Worksome\Graphlint\Inspections\NonNullableInsideListInspection;
use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('ignores list inspection by name', function (SmartFileInfo $smartFileInfo) {
    $inspection = $this->app->get(NonNullableInsideListInspection::class);
    $suppressor = $this->app->get(IgnoreByNameSuppressorInspection::class);
    $suppressor->configure('User.alwaysTwoEntries');

    expect($smartFileInfo)
        ->toPassInspection($inspection, $suppressor);
})->with(getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/IgnoreByNameSuppressorInspectionTest'
));
