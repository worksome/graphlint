<?php

namespace Olivernybroe\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use Illuminate\Support\Str;
use Olivernybroe\Graphlint\Fixes\CamelCaseNameFixer;
use Olivernybroe\Graphlint\ProblemsHolder;
use Olivernybroe\Graphlint\Utils\NodeNameResolver;

class CamelCaseFieldDefinitionInspection extends Inspection
{
    public function __construct(
        private NodeNameResolver $nameResolver,
        private CamelCaseNameFixer $camelCaseNameFixer,
    ) {}

    public function visitFieldDefinition(
        ProblemsHolder $problemsHolder,
        FieldDefinitionNode $fieldDefinitionNode,
    ): void {
        $name = $this->nameResolver->getName($fieldDefinitionNode);

        if ($name === Str::camel($name)) {
            return;
        }

        $problemsHolder->registerProblem(
            $fieldDefinitionNode->name,
            $this->camelCaseNameFixer,
        );
    }
}