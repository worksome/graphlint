<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\DescriptionRequiredInspection;

use function Worksome\Graphlint\Tests\app;
use function Worksome\Graphlint\Tests\yieldFixtures;

it('can require description', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(DescriptionRequiredInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(yieldFixtures(
    __DIR__ . '/../../../test-resources/Inspections/DescriptionRequiredInspectionTest'
));
