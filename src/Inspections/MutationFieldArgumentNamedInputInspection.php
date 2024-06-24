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
        private readonly NodeNameResolver $nameResolver,
        private readonly RenameFixer $renameFixer,
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
            // Reject arguments which are named `input`
            ->reject(fn(InputValueDefinitionNode $node) => $this->nameResolver->getName($node) === 'input')
            // Register a problem on each of the arguments
            ->each(fn(InputValueDefinitionNode $node) => $problemsHolder->registerProblemWithDescription(
                $node->name,
                $this->definition()->getTitle(),
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
