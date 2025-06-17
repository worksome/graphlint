<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixes;

use GraphQL\Language\AST\NameNode;
use Jawira\CaseConverter\Convert;
use Worksome\Graphlint\ProblemDescriptor;
use Worksome\Graphlint\Utils\NodeNameResolver;

class UpperSnakeCaseNameFixer extends Fixer
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    public function fix(ProblemDescriptor $problemDescriptor): void
    {
        $node = $problemDescriptor->getNode();

        if (! $node instanceof NameNode) {
            return;
        }

        $name = $this->nodeNameResolver->getName($node);

        if ($name === null) {
            return;
        }

        $node->value = (new Convert($name))->fromAuto(false)->toMacro();
    }
}
