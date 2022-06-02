<?php

namespace Worksome\Graphlint\Tests\Unit\Inspections;

use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use Worksome\Graphlint\Inspections\InputSuffixInputObjectTypeDefinitionInspection;
use Worksome\Graphlint\Inspections\MutationFieldArgumentNamedInputInspection;
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