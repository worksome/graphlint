<?php

namespace Olivernybroe\Graphlint\Tests\Unit\Inspections;

use Olivernybroe\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use Olivernybroe\Graphlint\Inspections\InputSuffixInputObjectTypeDefinitionInspection;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

it('can rename input object type', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(InputSuffixInputObjectTypeDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);

})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/InputSuffixInputObjectTypeDefinitionInspectionTest',
    '*.graphql.inc',
));