<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use GraphQL\Language\AST\DocumentNode;

class EmptyDocumentNode extends DocumentNode
{
    public function __construct()
    {
        parent::__construct([]);
    }
}
