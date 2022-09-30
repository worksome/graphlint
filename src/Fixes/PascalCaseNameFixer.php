<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixes;

use GraphQL\Language\AST\NameNode;
use Illuminate\Support\Str;
use Worksome\Graphlint\ProblemDescriptor;
use Worksome\Graphlint\Utils\NodeNameResolver;

class PascalCaseNameFixer extends Fixer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
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

        $pascalCase = Str::of($name)
            ->camel()
            ->ucfirst();

        $node->value = $pascalCase->__toString();
    }
}
