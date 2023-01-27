<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\Node;

class NodeNameResolver
{
    public function getName(?Node $node): ?string
    {
        if ($node === null) {
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
