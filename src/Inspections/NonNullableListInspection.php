<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQL\Language\AST\TypeDefinitionNode;
use Worksome\Graphlint\Fixes\NonNullFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;

class NonNullableListInspection extends Inspection
{
    private bool $onlyOutputTypes = true;

    public function __construct(
        private readonly NonNullFixer $nonNullFixer,
    ) {
    }

    public function visitListType(
        ProblemsHolder $problemsHolder,
        ListTypeNode $listTypeNode,
        Node $parent,
        array $ancestors,
    ): void {
        // If parent is non-nullable, then the list cannot be null
        if ($parent instanceof NonNullTypeNode) {
            return;
        }

        if ($this->onlyOutputTypes) {
            // Get the type definition which contains our array
            $typeDefinitionNode = null;
            foreach (array_reverse($ancestors) as $ancestor) {
                if ($ancestor instanceof TypeDefinitionNode) {
                    $typeDefinitionNode = $ancestor;
                }
            }

            // Check if output type
            if ($typeDefinitionNode instanceof InputObjectTypeDefinitionNode) {
                return;
            }

            if ($parent instanceof InputValueDefinitionNode) {
                return;
            }
        }

        $problemsHolder->registerProblemWithDescription(
            $listTypeNode,
            $this->definition()->getTitle(),
            $this->nonNullFixer
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            'Lists must be non nullable.',
        );
    }

    public function configure(
        bool $onlyOutputTypes = true,
    ): void {
        $this->onlyOutputTypes = $onlyOutputTypes;
    }
}
