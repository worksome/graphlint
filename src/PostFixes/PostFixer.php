<?php

declare(strict_types=1);

namespace Worksome\Graphlint\PostFixes;

use GraphQL\Language\AST\Node;

abstract class PostFixer
{
    public abstract function visitNode(Node $node): ?Node;
}
