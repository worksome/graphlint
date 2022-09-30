<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixes;

use GraphQL\Language\AST\NameNode;
use Worksome\Graphlint\ProblemDescriptor;
use Worksome\Graphlint\Utils\NodeNameResolver;

class SuffixNameFixer extends Fixer
{
    private string $suffix;

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

        $node->value = "{$this->nodeNameResolver->getName($node)}$this->suffix";
    }

    public function withSuffix(string $suffix): self
    {
        $this->suffix = $suffix;
        return $this;
    }
}
