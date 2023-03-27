<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\DisallowEnumInspection;

use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can disallow enums', function (SmartFileInfo $smartFileInfo) {
    $inspection = $this->app->get(DisallowEnumInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/DisallowEnumInspectionTest'
));
