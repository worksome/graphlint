<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\NonNullTypeNode;
use Worksome\Graphlint\Fixes\NonNullFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\NodeNameResolver;

class NonNullableIdInspection extends Inspection
{
    public function __construct(
        private readonly NodeNameResolver $nameResolver,
        private readonly NonNullFixer $nonNullFixer,
    ) {
    }

    public function visitFieldDefinition(
        ProblemsHolder $problemsHolder,
        FieldDefinitionNode $fieldDefinitionNode,
    ): void {
        $name = $this->nameResolver->getName($fieldDefinitionNode);

        if ($name !== 'id') {
            return;
        }

        $type = $fieldDefinitionNode->type;

        if ($type instanceof NonNullTypeNode) {
            return;
        }

        $problemsHolder->registerProblemWithDescription(
            $type,
            $this->definition()->getTitle(),
            $this->nonNullFixer
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            'Fields named ID must be non nullable.',
        );
    }
}
