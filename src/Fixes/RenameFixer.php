<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixes;

use GraphQL\Language\AST\NameNode;
use Worksome\Graphlint\ProblemDescriptor;

class RenameFixer extends Fixer
{
    private string $newName;

    public function fix(ProblemDescriptor $problemDescriptor): void
    {
        $node = $problemDescriptor->getNode();

        if (! $node instanceof NameNode) {
            return;
        }

        $node->value = $this->newName;
    }

    public function withName(string $name): self
    {
        $this->newName = $name;
        return $this;
    }
}
