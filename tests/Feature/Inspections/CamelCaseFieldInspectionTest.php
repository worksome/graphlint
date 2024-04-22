<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symfony\Component\Finder\SplFileInfo;
use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can rename field', function (SplFileInfo $fileInfo) {
    $inspection = $this->app->get(CamelCaseFieldDefinitionInspection::class);

    expect($fileInfo)
        ->toPassInspection($inspection);
})->with(fn () => getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/CamelCaseFieldInspectionTest'
));
