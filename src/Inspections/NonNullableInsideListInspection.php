<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NonNullTypeNode;
use Worksome\Graphlint\Fixes\NonNullFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;

class NonNullableInsideListInspection extends Inspection
{
    public function __construct(
        private NonNullFixer $nonNullFixer,
    ) {
    }

    public function visitListType(
        ProblemsHolder $problemsHolder,
        ListTypeNode $listTypeNode,
        Node $parent,
    ): void {
        $type = $listTypeNode->type;

        if ($type instanceof NonNullTypeNode) {
            return;
        }


        $problemsHolder->registerProblem(
            $type,
            $this->nonNullFixer
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            "Type inside of a list must be non nullable.",
        );
    }
}
