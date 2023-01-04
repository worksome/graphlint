<?php

declare(strict_types=1);

namespace Worksome\Graphlint\PostFixes;

use GraphQL\Language\AST\Node;
use Worksome\Graphlint\PostFixes\Collector\NodeReplacerCollector;

class NodeReplacerPostFixer extends PostFixer
{
    public function __construct(
        private readonly NodeReplacerCollector $nodeReplacerCollector,
    ) {
    }

    public function visitNode(Node $node): ?Node
    {
        $nodes = $this->nodeReplacerCollector->getNodesToReplace();

        foreach ($nodes as [$originalNode, $replacementNode]) {
            if ($node === $originalNode) {
                return $replacementNode;
            }
        }

        return null;
    }
}
