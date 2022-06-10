<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\MutationFieldArgumentNamedInputInspection;

use function Worksome\Graphlint\Tests\app;
use function Worksome\Graphlint\Tests\yieldFixtures;

it('can rename argument field', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(MutationFieldArgumentNamedInputInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(yieldFixtures(
    __DIR__ . '/../../../test-resources/Inspections/MutationFieldArgumentNamedInputInspectionTest'
));
