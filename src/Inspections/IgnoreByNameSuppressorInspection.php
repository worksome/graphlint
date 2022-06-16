<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\Node;
use Worksome\Graphlint\Contracts\SuppressorInspection;
use Worksome\Graphlint\Utils\NodeNameResolver;

class IgnoreByNameSuppressorInspection implements SuppressorInspection
{
    /**
     * @var string[]
     */
    private array $names = [];

    public function __construct(
        private readonly NodeNameResolver $nameResolver,
    ) {
    }

    public function shouldSuppress(Node $node, array $parents, Inspection $inspection): bool
    {
        $name = $this->nameResolver->getName($node);

        $parent = end($parents);
        if ($parent === false || $parent instanceof NodeList) {
            $parentName = null;
        } else {
            $parentName = $this->nameResolver->getName($parent);
        }

        if ($name === null) {
            return false;
        }

        // Check if name in names
        if (in_array($name, $this->names)) {
            return true;
        }

        // Check if name dotted with parents in names
        if (in_array("$parentName.$name", $this->names)) {
            return true;
        }

        return false;
    }

    public function configure(string ...$names): void
    {
        $this->names = $names;
    }
}
