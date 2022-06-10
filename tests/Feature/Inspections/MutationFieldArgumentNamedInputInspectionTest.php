<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature\Inspections;

use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SmartFileSystem\SmartFileInfo;
use Worksome\Graphlint\Inspections\MutationFieldArgumentNamedInputInspection;

use function Worksome\Graphlint\Tests\app;

it('can rename argument field', function (SmartFileInfo $smartFileInfo) {
    $inspection = app()->get(MutationFieldArgumentNamedInputInspection::class);

    expect($smartFileInfo)
        ->toPassInspection($inspection);
})->with(StaticFixtureFinder::yieldDirectory(
    __DIR__ . '/../../../test-resources/Inspections/MutationFieldArgumentNamedInputInspectionTest',
    '*.graphql.inc',
));
