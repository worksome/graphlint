<?php

namespace Worksome\Graphlint\Tests\Unit\Inspections;

use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use Worksome\Graphlint\Inspections\DisallowEnumInspection;
use Worksome\Graphlint\Inspections\ModelDirectiveRequiresIdFieldInspection;
use Psr\Container\ContainerInterface;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

it('can disallow enums', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(ModelDirectiveRequiresIdFieldInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/ModelDirectiveRequiresIdFieldInspectionTest',
    '*.graphql.inc',
));