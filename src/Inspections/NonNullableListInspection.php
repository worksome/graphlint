<?php

namespace Olivernybroe\Graphlint\Inspections;

use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NonNullTypeNode;
use Olivernybroe\Graphlint\Fixes\NonNullFixer;
use Olivernybroe\Graphlint\ProblemsHolder;

class NonNullableListInspection extends Inspection
{
    public function __construct(
        private NonNullFixer $nonNullFixer,
    ) {}

    public function visitListType(
        ProblemsHolder $problemsHolder,
        ListTypeNode $listTypeNode,
        Node $parent,
    ): void
    {
        if ($parent instanceof NonNullTypeNode) {
            return;
        }

        $problemsHolder->registerProblem(
            $listTypeNode,
            $this->nonNullFixer
        );
    }
}