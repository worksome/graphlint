<?php

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\NonNullTypeNode;
use Worksome\Graphlint\Fixes\NonNullFixer;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\NodeNameResolver;

class NonNullableIdInspection extends Inspection
{
    public function __construct(
        private NodeNameResolver $nameResolver,
        private NonNullFixer $nonNullFixer,
    ) {}

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

        $problemsHolder->registerProblem(
            $type,
            $this->nonNullFixer
        );
    }
}