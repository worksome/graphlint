<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixes;

use GraphQL\Language\AST\NonNullTypeNode;
use GraphQL\Language\AST\TypeNode;
use Worksome\Graphlint\PostFixes\Collector\NodeReplacerCollector;
use Worksome\Graphlint\ProblemDescriptor;

class NonNullFixer extends Fixer
{
    public function __construct(
        private readonly NodeReplacerCollector $nodeReplacerCollector,
    ) {
    }

    public function fix(ProblemDescriptor $problemDescriptor): void
    {
        $node = $problemDescriptor->getNode();

        if (! $node instanceof TypeNode) {
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
