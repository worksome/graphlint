<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixes;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Worksome\Graphlint\ProblemDescriptor;

class AddFieldFixer extends Fixer
{
    private FieldDefinitionNode $fieldDefinitionNode;

    private bool $fieldAtTop;

    public function fix(ProblemDescriptor $problemDescriptor): void
    {
        $node = $problemDescriptor->getNode();

        if (!$node instanceof ObjectTypeDefinitionNode) {
            return;
        }

        if ($this->fieldAtTop) {
            $fields = iterator_to_array($node->fields);
            array_unshift(
                $fields,
                $this->fieldDefinitionNode,
            );

            $node->fields = new NodeList(
                $fields
            );
        } else {
            $node->fields[] = $this->fieldDefinitionNode;
        }
    }

    public function withFieldDefinitionNode(FieldDefinitionNode $fieldDefinitionNode): self
    {
        $this->fieldDefinitionNode = $fieldDefinitionNode;
        return $this;
    }

    public function atTop(bool $top = true): self
    {
        $this->fieldAtTop = $top;
        return $this;
    }
}
