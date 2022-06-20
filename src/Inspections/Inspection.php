<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\ArgumentNode;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\EnumValueDefinitionNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
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
        InputObjectTypeDefinitionNode $inputObjectTypeDefinitionNode,
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

    /**
     * @param Node[] $ancestors
     */
    public function visitListType(
        ProblemsHolder $problemsHolder,
        ListTypeNode $listTypeNode,
        Node $parent,
        array $ancestors,
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

    public function visitScalarTypeDefinition(
        ProblemsHolder $problemsHolder,
        ScalarTypeDefinitionNode $scalarTypeDefinitionNode,
    ): void {
    }

    public function visitInputValueDefinition(
        ProblemsHolder $problemsHolder,
        InputValueDefinitionNode $inputValueDefinitionNode,
    ): void {
    }

    public function visitInterfaceTypeDefinition(
        ProblemsHolder $problemsHolder,
        InterfaceTypeDefinitionNode $interfaceTypeDefinitionNode,
    ): void {
    }

    public function visitUnionTypeDefinition(
        ProblemsHolder $problemsHolder,
        UnionTypeDefinitionNode $unionTypeDefinitionNode,
    ): void {
    }

    public function visitEnumValueDefinition(
        ProblemsHolder $problemsHolder,
        EnumValueDefinitionNode $enumValueDefinitionNode,
    ): void {
    }

    public function __toString()
    {
        return class_basename($this);
    }
}
