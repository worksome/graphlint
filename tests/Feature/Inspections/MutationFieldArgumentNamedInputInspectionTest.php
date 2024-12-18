<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symfony\Component\Finder\SplFileInfo;
use Worksome\Graphlint\Inspections\MutationFieldArgumentNamedInputInspection;
use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can rename argument field', function (SplFileInfo $smartFileInfo) {
    $inspection = $this->app->get(MutationFieldArgumentNamedInputInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(fn () => getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/MutationFieldArgumentNamedInputInspectionTest'
));
