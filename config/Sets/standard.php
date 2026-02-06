<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\Configuration\Visitor;
use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;
use Worksome\Graphlint\Inspections\DescriptionRequiredInspection;
use Worksome\Graphlint\Inspections\InputSuffixInputObjectTypeDefinitionInspection;
use Worksome\Graphlint\Inspections\MutationFieldArgumentNamedInputInspection;
use Worksome\Graphlint\Inspections\NonNullableIdInspection;
use Worksome\Graphlint\Inspections\NonNullableInsideListInspection;
use Worksome\Graphlint\Inspections\NonNullableListInspection;
use Worksome\Graphlint\Inspections\PascalCaseObjectTypeDefinitionInspection;
use Worksome\Graphlint\Inspections\UpperSnakeCaseEnumCaseDefinitionInspection;

return function (ContainerConfigurator $config): void {
    $services = $config->services();

    $inspections = [
        CamelCaseFieldDefinitionInspection::class,
        InputSuffixInputObjectTypeDefinitionInspection::class,
        MutationFieldArgumentNamedInputInspection::class,
        NonNullableIdInspection::class,
        NonNullableInsideListInspection::class,
        NonNullableListInspection::class,
        PascalCaseObjectTypeDefinitionInspection::class,
        DescriptionRequiredInspection::class,
        UpperSnakeCaseEnumCaseDefinitionInspection::class,
    ];

    foreach ($inspections as $inspection) {
        $services->set($inspection)
            ->tag(Visitor::COMPILED);
    }
};
