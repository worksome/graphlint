<?php

namespace Olivernybroe\Graphlint\Inspections;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use Olivernybroe\Graphlint\ProblemsHolder;

class DisallowEnumInspection extends Inspection
{
    public function visitEnumTypeDefinition(
        ProblemsHolder $problemsHolder,
        EnumTypeDefinitionNode $enumTypeDefinitionNode,
    ): void {
        $problemsHolder->registerProblem(
            $enumTypeDefinitionNode
        );
    }
}