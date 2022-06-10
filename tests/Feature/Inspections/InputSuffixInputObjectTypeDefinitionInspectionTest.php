<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\InputSuffixInputObjectTypeDefinitionInspection;

use function Worksome\Graphlint\Tests\app;

it('can rename input object type', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(InputSuffixInputObjectTypeDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/InputSuffixInputObjectTypeDefinitionInspectionTest',
    '*.graphql.inc',
));
