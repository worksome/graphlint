<?php

namespace Olivernybroe\Graphlint\Tests\Unit\Inspections;

use Olivernybroe\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use Olivernybroe\Graphlint\Inspections\InputSuffixInputObjectTypeDefinitionInspection;
use Olivernybroe\Graphlint\Inspections\MutationFieldArgumentNamedInputInspection;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

it('can rename argument field', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(MutationFieldArgumentNamedInputInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/MutationFieldArgumentNamedInputInspectionTest',
    '*.graphql.inc',
));