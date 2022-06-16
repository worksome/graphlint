<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Contracts;

use GraphQL\Language\AST\Node;
use Worksome\Graphlint\Inspections\Inspection;

interface SuppressorInspection
{
    /**
     * @param Node[] $parents
     */
    public function shouldSuppress(Node $node, array $parents, Inspection $inspection): bool;
}
