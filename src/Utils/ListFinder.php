<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

use GraphQL\Language\AST\Node;

class ListFinder
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
    ) {
    }

    /**
     * @param iterable<int|string, Node> $list
     */
    public function contains(iterable $list, string $name): bool
    {
        foreach ($list as $directive) {
            if ($this->nodeNameResolver->getName($directive) === $name) {
                return true;
            }
        }

        return false;
    }
}
