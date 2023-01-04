<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixer;

use GraphQL\Language\AST\DocumentNode;

class FixerResult
{
    public function __construct(
        private readonly DocumentNode $originalDocumentNode,
        private readonly DocumentNode $documentNode,
    ) {
    }

    public function getDocumentNode(): DocumentNode
    {
        return $this->documentNode;
    }

    public function getOriginalDocumentNode(): DocumentNode
    {
        return $this->originalDocumentNode;
    }
}
