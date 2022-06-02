<?php

namespace Worksome\Graphlint\Utils;

use GraphQL\Language\AST\DirectiveNode;
use GraphQL\Language\AST\NodeList;

class ListFinder
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
    ) {}

    /**
     * @param NodeList<DirectiveNode> $list
     */
    public function contains(NodeList $list, string $name): bool
    {
        foreach ($list as $directive) {
            if ($this->nodeNameResolver->getName($directive) === $name) {
                return true;
            }
        }

        return false;
    }
}