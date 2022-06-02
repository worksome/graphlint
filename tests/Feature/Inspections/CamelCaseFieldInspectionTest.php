<?php

namespace Worksome\Graphlint\Tests\Unit\Inspections;

use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use Psr\Container\ContainerInterface;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

it('can rename field', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(CamelCaseFieldDefinitionInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);

})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/CamelCaseFieldInspectionTest',
    '*.graphql.inc',
));