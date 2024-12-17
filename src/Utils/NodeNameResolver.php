<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeList;

class NodeNameResolver
{
    /**
     * @param Node|NodeList<Node>|null $node
     */
    public function getName(Node|NodeList|null $node): string|null
    {
        if (! $node instanceof Node) {
            return null;
        }

        if ($node instanceof NameNode) {
            return $node->value;
        }

        if (! isset($node->name)) {
            return null;
        }

        return $this->getName($node->name);
    }
}
