<?php

namespace Worksome\Graphlint;

use GraphQL\Language\AST\Node;
use Worksome\Graphlint\Fixes\Fixer;

class ProblemDescriptor
{
    public function __construct(
        private Node   $node,
        private ?Fixer $fix,
    ) {}

    public function getNode(): Node
    {
        return $this->node;
    }

    public function getFix(): ?Fixer
    {
        return $this->fix;
    }
}