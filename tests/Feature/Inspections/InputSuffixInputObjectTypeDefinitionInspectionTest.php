<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symfony\Component\Finder\SplFileInfo;
use Worksome\Graphlint\Inspections\InputSuffixInputObjectTypeDefinitionInspection;
use function Worksome\Graphlint\Tests\getFixturesForDirectory;

it('can rename input object type', function (SplFileInfo $smartFileInfo) {
    $inspection = $this->app->get(InputSuffixInputObjectTypeDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(fn () => getFixturesForDirectory(
    __DIR__ . '/../../../test-resources/Inspections/InputSuffixInputObjectTypeDefinitionInspectionTest'
));
