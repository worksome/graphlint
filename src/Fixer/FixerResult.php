<?php

namespace Worksome\Graphlint\Fixer;

use GraphQL\Language\AST\DocumentNode;

class FixerResult
{
    public function __construct(
        private DocumentNode $originalDocumentNode,
        private DocumentNode $documentNode,
    ) {}

    public function getDocumentNode(): DocumentNode
    {
        return $this->documentNode;
    }

    public function getOriginalDocumentNode(): DocumentNode
    {
        return $this->originalDocumentNode;
    }
}