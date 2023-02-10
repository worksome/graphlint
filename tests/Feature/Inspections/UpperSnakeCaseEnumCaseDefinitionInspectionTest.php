<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\UpperSnakeCaseEnumCaseDefinitionInspection;
use function Worksome\Graphlint\Tests\app;
use function Worksome\Graphlint\Tests\yieldFixtures;

it('can rename type', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(UpperSnakeCaseEnumCaseDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(yieldFixtures(
    __DIR__ . '/../../../test-resources/Inspections/UpperSnakeCaseEnumCaseDefinitionInspectionTest'
));
