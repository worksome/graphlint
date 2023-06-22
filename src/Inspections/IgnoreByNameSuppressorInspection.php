<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeList;
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
        $paths = $this->paths([...$parents, $node]);
        foreach ($paths as $path) {
            if (in_array($path, $this->names)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<Node|NodeList> $nodes A list of nodes where the first parent and the following entries are children
     *
     * @return list<string> A list of increasingly specific paths, e.g. ['Parent', 'Parent.field', 'Parent.field.argument']
     */
    protected function paths(array $nodes): array
    {
        $paths = [];
        foreach (array_reverse($nodes) as $node) {
            $name = $this->nameResolver->getName($node);
            if ($name === null) {
                continue;
            }
            foreach ($paths as &$path) {
                $path = "$name.$path";
            }
            array_unshift($paths, $name);
        }
        return $paths;
    }

    public function configure(string ...$names): void
    {
        $this->names = $names;
    }
}
