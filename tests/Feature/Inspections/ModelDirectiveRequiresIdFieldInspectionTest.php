<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symfony\Component\Finder\SplFileInfo;
use Worksome\Graphlint\Inspections\ModelDirectiveRequiresIdFieldInspection;
use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can disallow enums', function (SplFileInfo $smartFileInfo) {
    $inspection = $this->app->get(ModelDirectiveRequiresIdFieldInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(fn () => getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/ModelDirectiveRequiresIdFieldInspectionTest'
));
