<?php

namespace Olivernybroe\Graphlint\Tests\Unit\Inspections;

use Olivernybroe\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use Olivernybroe\Graphlint\Inspections\DisallowEnumInspection;
use Psr\Container\ContainerInterface;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

it('can disallow enums', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(DisallowEnumInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/DisallowEnumInspectionTest',
    '*.graphql.inc',
));