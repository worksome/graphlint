<?php

namespace Olivernybroe\Graphlint\Fixes;

use GraphQL\Language\AST\NameNode;
use Illuminate\Support\Str;
use Olivernybroe\Graphlint\ProblemDescriptor;
use Olivernybroe\Graphlint\Utils\NodeNameResolver;

class CamelCaseNameFixer extends Fixer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
    ) {}

    public function fix(ProblemDescriptor $problemDescriptor): void
    {
        $node = $problemDescriptor->getNode();

        if (!$node instanceof NameNode) {
            return;
        }

        $camelCasedName = Str::camel($this->nodeNameResolver->getName($node));

        $node->value = $camelCasedName;
    }
}