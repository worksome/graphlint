<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\DescriptionRequiredInspection;

use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can require description', function (SmartFileInfo $smartFileInfo) {
    $inspection = $this->app->get(DescriptionRequiredInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/DescriptionRequiredInspectionTest'
));
