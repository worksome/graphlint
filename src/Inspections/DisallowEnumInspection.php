<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;

class DisallowEnumInspection extends Inspection
{
    public function visitEnumTypeDefinition(
        ProblemsHolder $problemsHolder,
        EnumTypeDefinitionNode $enumTypeDefinitionNode,
    ): void {
        $problemsHolder->registerProblemWithDescription(
            $enumTypeDefinitionNode,
            $this->definition()->getTitle()
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            "Enums are not allowed.",
        );
    }
}
