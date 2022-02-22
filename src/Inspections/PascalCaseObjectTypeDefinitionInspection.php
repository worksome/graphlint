<?php

namespace Olivernybroe\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Illuminate\Support\Str;
use Olivernybroe\Graphlint\Fixes\CamelCaseNameFixer;
use Olivernybroe\Graphlint\Fixes\PascalCaseNameFixer;
use Olivernybroe\Graphlint\ProblemsHolder;
use Olivernybroe\Graphlint\Utils\NodeNameResolver;

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