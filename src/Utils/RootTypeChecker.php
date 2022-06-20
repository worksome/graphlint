<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

use GraphQL\Language\AST\NameNode;

class RootTypeChecker
{
    public function __construct(
        private readonly NodeNameResolver $nameResolver,
    ) {
    }

    public function isRootName(NameNode $nameNode): bool
    {
        $name = $this->nameResolver->getName($nameNode);

        if ($name === null) {
            return false;
        }

        return RootTypeName::tryFrom($name) !== null;
    }
}
