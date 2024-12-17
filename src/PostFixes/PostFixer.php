<?php

declare(strict_types=1);

namespace Worksome\Graphlint\PostFixes;

use GraphQL\Language\AST\Node;

abstract class PostFixer
{
    abstract public function visitNode(Node $node): Node|null;
}
