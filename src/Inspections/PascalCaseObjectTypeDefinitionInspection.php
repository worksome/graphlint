<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Illuminate\Support\Str;
use Worksome\Graphlint\Fixes\PascalCaseNameFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\ApolloFederationChecker;
use Worksome\Graphlint\Utils\NodeNameResolver;

class PascalCaseObjectTypeDefinitionInspection extends Inspection
{
    public function __construct(
        private readonly NodeNameResolver $nameResolver,
        private readonly PascalCaseNameFixer $pascalCaseNameFixer,
        private readonly ApolloFederationChecker $apolloFederationChecker,
    ) {
    }

    public function visitObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        ObjectTypeDefinitionNode $objectTypeDefinitionNode,
    ): void {
        $name = $this->nameResolver->getName($objectTypeDefinitionNode);

        if ($name === null) {
            return;
        }

        if ($this->apolloFederationChecker->isApolloTypeName($objectTypeDefinitionNode->name)) {
            return;
        }

        $pascalCase = Str::of($name)->camel()->ucfirst()->__toString();

        if ($name === $pascalCase) {
            return;
        }

        $problemsHolder->registerProblem(
            $objectTypeDefinitionNode->name,
            $this->pascalCaseNameFixer,
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            "Object types must be PascalCase.",
        );
    }
}
