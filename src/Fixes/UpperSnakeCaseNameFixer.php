<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixes;

use GraphQL\Language\AST\NameNode;
use Worksome\Graphlint\ProblemDescriptor;
use Worksome\Graphlint\Utils\NodeNameResolver;
use Worksome\Graphlint\Utils\UpperSnakeCaseConverter;

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

        $node->value = UpperSnakeCaseConverter::convert($name);
    }
}
