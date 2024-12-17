<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use Illuminate\Support\Str;
use Worksome\Graphlint\Fixes\CamelCaseNameFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\ApolloFederationChecker;
use Worksome\Graphlint\Utils\NodeNameResolver;

class CamelCaseFieldDefinitionInspection extends Inspection
{
    public function __construct(
        private readonly NodeNameResolver $nameResolver,
        private readonly CamelCaseNameFixer $camelCaseNameFixer,
        private readonly ApolloFederationChecker $apolloFederationChecker,
    ) {
    }

    public function visitFieldDefinition(
        ProblemsHolder $problemsHolder,
        FieldDefinitionNode $fieldDefinitionNode,
    ): void {
        $name = $this->nameResolver->getName($fieldDefinitionNode);

        if ($name === null) {
            return;
        }

        if ($name === Str::camel($name)) {
            return;
        }

        if ($this->apolloFederationChecker->isApolloFieldDefinition($fieldDefinitionNode)) {
            return;
        }

        $problemsHolder->registerProblemWithDescription(
            $fieldDefinitionNode->name,
            $this->definition()->getTitle(),
            $this->camelCaseNameFixer,
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            'Fields should always be written in CamelCase.',
        );
    }
}
