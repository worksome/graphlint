<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\EnumValueDefinitionNode;
use Jawira\CaseConverter\Convert;
use Worksome\Graphlint\Fixes\UpperSnakeCaseNameFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\ApolloFederationChecker;
use Worksome\Graphlint\Utils\NodeNameResolver;

class UpperSnakeCaseEnumCaseDefinitionInspection extends Inspection
{
    public function __construct(
        private readonly NodeNameResolver $nameResolver,
        private readonly UpperSnakeCaseNameFixer $upperCaseNameFixer,
        private readonly ApolloFederationChecker $apolloFederationChecker,
    ) {
    }

    public function visitEnumValueDefinition(
        ProblemsHolder $problemsHolder,
        EnumValueDefinitionNode $enumValueDefinitionNode,
    ): void {
        $name = $this->nameResolver->getName($enumValueDefinitionNode);

        if ($name === null) {
            return;
        }

        if ($this->apolloFederationChecker->isApolloDefinitionName($enumValueDefinitionNode->name)) {
            return;
        }

        $upperCase = (new Convert($name))->fromAuto(false)->toMacro();

        if ($name === $upperCase) {
            return;
        }

        $problemsHolder->registerProblemWithDescription(
            $enumValueDefinitionNode->name,
            $this->definition()->getTitle(),
            $this->upperCaseNameFixer,
        );
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            'Enum cases must be UPPER_CASE.',
        );
    }
}
