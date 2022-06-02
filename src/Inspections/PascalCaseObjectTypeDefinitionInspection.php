<?php

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Illuminate\Support\Str;
use Worksome\Graphlint\Fixes\CamelCaseNameFixer;
use Worksome\Graphlint\Fixes\PascalCaseNameFixer;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\NodeNameResolver;

class PascalCaseObjectTypeDefinitionInspection extends Inspection
{
    public function __construct(
        private NodeNameResolver $nameResolver,
        private PascalCaseNameFixer $pascalCaseNameFixer,
    ) {}

    public function visitObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        ObjectTypeDefinitionNode $objectTypeDefinitionNode,
    ): void {
        $name = $this->nameResolver->getName($objectTypeDefinitionNode);

        $pascalCase = Str::of($name)->camel()->ucfirst()->__toString();

        if ($name === $pascalCase) {
            return;
        }

        $problemsHolder->registerProblem(
            $objectTypeDefinitionNode->name,
            $this->pascalCaseNameFixer,
        );
    }
}