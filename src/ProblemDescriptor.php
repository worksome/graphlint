<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use GraphQL\Language\AST\Node;
use Worksome\Graphlint\Fixes\Fixer;

class ProblemDescriptor
{
    public function __construct(
        private readonly Node $node,
        private readonly string|null $description,
        private readonly ?Fixer $fix,
    ) {
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function getFix(): ?Fixer
    {
        return $this->fix;
    }

    public function getDescription(): string|null
    {
        return $this->description;
    }
}
