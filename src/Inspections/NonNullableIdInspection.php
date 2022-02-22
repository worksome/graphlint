<?php

namespace Olivernybroe\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\NonNullTypeNode;
use Olivernybroe\Graphlint\Fixes\NonNullFixer;
use Olivernybroe\Graphlint\ProblemsHolder;
use Olivernybroe\Graphlint\Utils\NodeNameResolver;

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