<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NonNullTypeNode;
use Worksome\Graphlint\Fixes\NonNullFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\ApolloFederationChecker;

class NonNullableInsideListInspection extends Inspection
{
    public function __construct(
        private readonly NonNullFixer $nonNullFixer,
        private readonly ApolloFederationChecker $apolloFederationChecker,
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

        if ($type instanceof NamedTypeNode && $this->apolloFederationChecker->isApolloTypeName($type->name)) {
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
