<?php

namespace Olivernybroe\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Illuminate\Support\Collection;
use Olivernybroe\Graphlint\Fixes\RenameFixer;
use Olivernybroe\Graphlint\ProblemsHolder;
use Olivernybroe\Graphlint\Utils\NodeNameResolver;

class MutationFieldArgumentNamedInputInspection extends Inspection
{
    public function __construct(
        private NodeNameResolver $nameResolver,
        private RenameFixer $renameFixer,
    ) {}

    public function visitObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        ObjectTypeDefinitionNode $objectTypeDefinitionNode,
    ): void {
        $objectTypeName = $this->nameResolver->getName($objectTypeDefinitionNode);

        if ($objectTypeName !== 'Mutation') {
            return;
        }

        // Take all the fields
        Collection::make($objectTypeDefinitionNode->fields)
            // Get all arguments of the fields
            ->flatMap(fn(FieldDefinitionNode $node) => iterator_to_array($node->arguments))
            // Filter down to arguments which are not named `input`
            ->filter(fn(InputValueDefinitionNode $node) => $this->nameResolver->getName($node) !== 'input')
            // Register a problem on each of the arguments
            ->each(fn(InputValueDefinitionNode $node) => $problemsHolder->registerProblem(
                $node->name,
                $this->renameFixer->withName('input'),
            ));
    }


}