<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\PascalCaseObjectTypeDefinitionInspection;

use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can rename type', function (SmartFileInfo $smartFileInfo) {
    $inspection = $this->app->get(PascalCaseObjectTypeDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/PascalCaseObjectTypeDefinitionInspectionTest'
));
