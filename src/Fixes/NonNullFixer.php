<?php

namespace Olivernybroe\Graphlint\Fixes;

use GraphQL\Language\AST\NonNullTypeNode;
use GraphQL\Language\AST\TypeNode;
use Olivernybroe\Graphlint\PostFixes\Collector\NodeReplacerCollector;
use Olivernybroe\Graphlint\ProblemDescriptor;

class NonNullFixer extends Fixer
{
    public function __construct(
        private NodeReplacerCollector $nodeReplacerCollector,
    ) {}

    public function fix(ProblemDescriptor $problemDescriptor): void
    {
        $node = $problemDescriptor->getNode();

        if (!$node instanceof TypeNode) {
            return;
        }

        $this->nodeReplacerCollector->addNodeToReplace(
            $node,
            new NonNullTypeNode([
                'type' => $node,
            ])
        );
    }
}