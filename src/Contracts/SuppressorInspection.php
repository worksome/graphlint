<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Contracts;

use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeList;
use Worksome\Graphlint\Inspections\Inspection;

interface SuppressorInspection
{
    /**
     * @param Node|NodeList<Node>|null   $parent
     * @param array<Node|NodeList<Node>> $ancestors
     */
    public function shouldSuppress(
        Node $node,
        Node|NodeList|null $parent,
        array $ancestors,
        Inspection $inspection,
    ): bool;
}
