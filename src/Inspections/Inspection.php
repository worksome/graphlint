<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\ArgumentNode;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Stringable;
use Worksome\Graphlint\Contracts\InspectionDescriptor;
use Worksome\Graphlint\ProblemsHolder;

abstract class Inspection implements Stringable, InspectionDescriptor
{
    public function visitFieldDefinition(
        ProblemsHolder $problemsHolder,
        FieldDefinitionNode $fieldDefinitionNode,
    ): void {
    }

    public function visitInputObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        InputObjectTypeDefinitionNode $fieldDefinitionNode,
    ): void {
    }

    public function visitArgumentNode(
        ProblemsHolder $problemsHolder,
        ArgumentNode $argumentNode,
    ): void {
    }

    public function visitObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        ObjectTypeDefinitionNode $objectTypeDefinitionNode,
    ): void {
    }

    public function visitListType(
        ProblemsHolder $problemsHolder,
        ListTypeNode $listTypeNode,
        Node $parent,
    ): void {
    }

    public function visitNode(
        ProblemsHolder $problemsHolder,
        Node $node
    ): void {
    }

    public function visitEnumTypeDefinition(
        ProblemsHolder $problemsHolder,
        EnumTypeDefinitionNode $enumTypeDefinitionNode,
    ): void {
    }

    public function __toString()
    {
        return class_basename($this);
    }
}
