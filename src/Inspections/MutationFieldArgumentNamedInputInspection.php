<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Illuminate\Support\Collection;
use Worksome\Graphlint\Fixes\RenameFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\NodeNameResolver;

class MutationFieldArgumentNamedInputInspection extends Inspection
{
    public function __construct(
        private NodeNameResolver $nameResolver,
        private RenameFixer $renameFixer,
    ) {
    }

    public function visitObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        ObjectTypeDefinitionNode $objectTypeDefinitionNode,
    ): void {
        $objectTypeName = $this->nameResolver->getName($objectTypeDefinitionNode);

        if ($objectTypeName !== 'Mutation') {
            return;
        }

        /** @var iterable<int|string, FieldDefinitionNode> $fields */
        $fields = $objectTypeDefinitionNode->fields;

        // Take all the fields
        Collection::make($fields)
            // Get all arguments of the fields
            ->flatMap(fn(FieldDefinitionNode $node) => iterator_to_array($node->arguments))
            // Filter down to arguments which are not named `input`
            /** @phpstan-ignore-next-line flatMap return type is wrong */
            ->filter(fn(InputValueDefinitionNode $node) => $this->nameResolver->getName($node) !== 'input')
            // Register a problem on each of the arguments
            /** @phpstan-ignore-next-line */
            ->each(fn(InputValueDefinitionNode $node) => $problemsHolder->registerProblem(
                $node->name,
                $this->renameFixer->withName('input'),
            ));
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            "Mutation field may have one argument named input.",
        );
    }
}
