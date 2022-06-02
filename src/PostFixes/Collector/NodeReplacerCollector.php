<?php

namespace Worksome\Graphlint\PostFixes\Collector;

use GraphQL\Language\AST\Node;

class NodeReplacerCollector
{
    /**
     * @var array<array<Node>>
     */
    private array $nodesToReplace = [];

    public function addNodeToReplace(Node $originalNode, Node $replacementNode): void
    {
        $this->nodesToReplace[] = [$originalNode, $replacementNode];
    }

    public function getNodesToReplace(): array
    {
        return $this->nodesToReplace;
    }
}