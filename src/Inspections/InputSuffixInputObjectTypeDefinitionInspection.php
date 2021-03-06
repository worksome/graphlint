<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use Illuminate\Support\Str;
use Worksome\Graphlint\Fixes\SuffixNameFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\NodeNameResolver;

class InputSuffixInputObjectTypeDefinitionInspection extends Inspection
{
    public function __construct(
        private NodeNameResolver $nameResolver,
        private SuffixNameFixer $suffixNameFixer,
    ) {
    }

    public function visitInputObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        InputObjectTypeDefinitionNode $fieldDefinitionNode,
    ): void {
        $name = $this->nameResolver->getName($fieldDefinitionNode);

        if ($name === null) {
            return;
        }

        if (Str::endsWith($name, 'Input')) {
            return;
        }

        $problemsHolder->registerProblem(
            $fieldDefinitionNode->name,
            $this->suffixNameFixer->withSuffix('Input'),
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            "Input objects should always be suffixed with input.",
        );
    }
}
