<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use Worksome\Graphlint\Fixes\AddFieldFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\ListFinder;

class ModelDirectiveRequiresIdFieldInspection extends Inspection
{
    public function __construct(
        private ListFinder $listFinder,
        private AddFieldFixer $addFieldFixer,
    ) {
    }

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

        $problemsHolder->registerProblemWithDescription(
            $objectTypeDefinitionNode,
            $this->definition()->getTitle(),
            $this->addFieldFixer->withFieldDefinitionNode(
                Parser::fieldDefinition("id: ID!")
            )->atTop()
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            "Object types with @model directive on it, must have an id field.",
        );
    }
}
