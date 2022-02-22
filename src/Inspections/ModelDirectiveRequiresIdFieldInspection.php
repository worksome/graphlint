<?php

namespace Olivernybroe\Graphlint\Inspections;

use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use Olivernybroe\Graphlint\Fixes\AddFieldFixer;
use Olivernybroe\Graphlint\ProblemsHolder;
use Olivernybroe\Graphlint\Utils\ListFinder;

class ModelDirectiveRequiresIdFieldInspection extends Inspection
{
    public function __construct(
        private ListFinder $listFinder,
        private AddFieldFixer $addFieldFixer,
    ) {}

    public function visitObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        ObjectTypeDefinitionNode $objectTypeDefinitionNode,
    ): void {
        $hasModelDirective = $this->listFinder->contains(
            $objectTypeDefinitionNode->directives,
        "model",
        );

        if (! $hasModelDirective) {
            return;
        }

        $hasIdField = $this->listFinder->contains(
            $objectTypeDefinitionNode->fields,
            "id",
        );

        if ($hasIdField) {
            return;
        }

        $problemsHolder->registerProblem(
            $objectTypeDefinitionNode,
            $this->addFieldFixer->withFieldDefinitionNode(
                Parser::fieldDefinition("id: ID!")
            )->atTop()
        );
    }

}