<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\EnumValueDefinitionNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\ApolloFederationChecker;
use Worksome\Graphlint\Utils\RootTypeChecker;

class DescriptionRequiredInspection extends Inspection
{
    public function __construct(
        private readonly ApolloFederationChecker $apolloFederationChecker,
        private readonly RootTypeChecker         $baseTypeChecker,
    ) {
    }

    public function visitScalarTypeDefinition(
        ProblemsHolder $problemsHolder,
        ScalarTypeDefinitionNode $scalarTypeDefinitionNode,
    ): void {
        $this->visitDefinition($scalarTypeDefinitionNode, $problemsHolder);
    }

    public function visitObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        ObjectTypeDefinitionNode $objectTypeDefinitionNode,
    ): void {
        $this->visitDefinition($objectTypeDefinitionNode, $problemsHolder);
    }

    public function visitFieldDefinition(
        ProblemsHolder $problemsHolder,
        FieldDefinitionNode $fieldDefinitionNode,
    ): void {
        $this->visitDefinition($fieldDefinitionNode, $problemsHolder);
    }

    public function visitInputValueDefinition(
        ProblemsHolder $problemsHolder,
        InputValueDefinitionNode $inputValueDefinitionNode,
    ): void {
        $this->visitDefinition($inputValueDefinitionNode, $problemsHolder);
    }

    public function visitInterfaceTypeDefinition(
        ProblemsHolder $problemsHolder,
        InterfaceTypeDefinitionNode $interfaceTypeDefinitionNode,
    ): void {
        $this->visitDefinition($interfaceTypeDefinitionNode, $problemsHolder);
    }

    public function visitUnionTypeDefinition(
        ProblemsHolder $problemsHolder,
        UnionTypeDefinitionNode $unionTypeDefinitionNode,
    ): void {
        $this->visitDefinition($unionTypeDefinitionNode, $problemsHolder);
    }

    public function visitEnumTypeDefinition(
        ProblemsHolder $problemsHolder,
        EnumTypeDefinitionNode $enumTypeDefinitionNode,
    ): void {
        $this->visitDefinition($enumTypeDefinitionNode, $problemsHolder);
    }

    public function visitEnumValueDefinition(
        ProblemsHolder $problemsHolder,
        EnumValueDefinitionNode $enumValueDefinitionNode,
    ): void {
        $this->visitDefinition($enumValueDefinitionNode, $problemsHolder);
    }

    public function visitInputObjectTypeDefinition(
        ProblemsHolder $problemsHolder,
        InputObjectTypeDefinitionNode $inputObjectTypeDefinitionNode,
    ): void {
        $this->visitDefinition($inputObjectTypeDefinitionNode, $problemsHolder);
    }

    private function visitDefinition(Node $node, ProblemsHolder $problemsHolder): void
    {

        if (! property_exists($node, 'description')) {
            return;
        }

        if (! property_exists($node, 'name')) {
            return;
        }

        if ($this->baseTypeChecker->isRootName($node->name)) {
            return;
        }

        if ($this->apolloFederationChecker->isApolloDefinitionName($node->name)) {
            return;
        }

        if ($node instanceof FieldDefinitionNode && $this->apolloFederationChecker->isApolloFieldDefinition($node)) {
            return;
        }

        if ($this->hasDescription($node->description)) {
            return;
        }

        $problemsHolder->registerProblemWithDescription(
            $node,
            "'{$node->name->value}' node definition does not have a description."
        );
    }

    private function hasDescription(StringValueNode|null $descriptionNode): bool
    {
        if ($descriptionNode === null) {
            return false;
        }

        return $descriptionNode->value !== '';
    }

    public function definition(): InspectionDescription
    {
        return new InspectionDescription(
            "All definition nodes must have a description",
        );
    }
}
