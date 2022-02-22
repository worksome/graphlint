<?php

namespace Olivernybroe\Graphlint\Inspections;

use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use Illuminate\Support\Str;
use Olivernybroe\Graphlint\Fixes\SuffixNameFixer;
use Olivernybroe\Graphlint\ProblemsHolder;
use Olivernybroe\Graphlint\Utils\NodeNameResolver;

class InputSuffixInputObjectTypeDefinitionInspection extends Inspection
{
    public function __construct(
        private NodeNameResolver $nameResolver,
        private SuffixNameFixer $suffixNameFixer,
    ) {}

    public function visitInputObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        InputObjectTypeDefinitionNode $fieldDefinitionNode,
    ): void {
        $name = $this->nameResolver->getName($fieldDefinitionNode);

        if (Str::endsWith($name, 'Input')) {
            return;
        }

        $problemsHolder->registerProblem(
            $fieldDefinitionNode->name,
            $this->suffixNameFixer->withSuffix('Input'),
        );
    }
}