<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\NameNode;

class ApolloFederationChecker
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    public function isApolloFieldDefinition(FieldDefinitionNode $fieldDefinitionNode): bool
    {
        $name = $this->nodeNameResolver->getName($fieldDefinitionNode);

        if ($name === null) {
            return false;
        }

        if (! in_array($name, ['_entities', '_service'])) {
            return false;
        }

        return true;
    }

    public function isApolloTypeName(NameNode $name): bool
    {
        $name = $this->nodeNameResolver->getName($name);

        if ($name === null) {
            return false;
        }

        if (! in_array($name, ['_Entity', '_Service'])) {
            return false;
        }

        return true;
    }
}
