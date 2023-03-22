<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;

use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can rename field', function (SmartFileInfo $smartFileInfo) {
    $inspection = $this->app->get(CamelCaseFieldDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/CamelCaseFieldInspectionTest'
));
