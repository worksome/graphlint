<?php

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NonNullTypeNode;
use Worksome\Graphlint\Fixes\NonNullFixer;
use Worksome\Graphlint\ProblemsHolder;

class NonNullableInsideListInspection extends Inspection
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
        $type = $listTypeNode->type;

        if ($type instanceof NonNullTypeNode) {
            return;
        }


        $problemsHolder->registerProblem(
            $type,
            $this->nonNullFixer
        );
    }
}