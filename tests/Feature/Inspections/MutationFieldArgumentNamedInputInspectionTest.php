<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\MutationFieldArgumentNamedInputInspection;

use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can rename argument field', function (SmartFileInfo $smartFileInfo) {
    $inspection = $this->app->get(MutationFieldArgumentNamedInputInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/MutationFieldArgumentNamedInputInspectionTest'
));
