<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Visitors;

use Closure;
use GraphQL\Language\AST\ArgumentNode;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\EnumValueDefinitionNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
use GraphQL\Language\Visitor;
use Worksome\Graphlint\Analyser\AffectedInspections;
use Worksome\Graphlint\Contracts\SuppressorInspection;
use Worksome\Graphlint\Inspections\Inspection;
use Worksome\Graphlint\ProblemsHolder;

/**
 * @phpstan-import-type VisitorArray from Visitor
 */
abstract class VisitorCollector
{
    /**
     * @return Inspection[]
     */
    abstract public function getInspections(): iterable;

    /**
     * @return iterable<SuppressorInspection>
     */
    abstract public function getSuppressors(): iterable;

    /**
     * @return array<string, mixed>
     *
     * @phpstan-return VisitorArray
     */
    public function getVisitor(ProblemsHolder $problemsHolder, AffectedInspections $affectedInspections): array
    {
        /** @var array<int, VisitorArray> $visitors */
        $visitors = array_map(
            fn(Inspection $inspection) => [
                NodeKind::FIELD_DEFINITION => $this->wrapper(
                    fn(FieldDefinitionNode $fieldDefinitionNode) =>
                        $inspection->visitFieldDefinition($problemsHolder, $fieldDefinitionNode),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::INPUT_OBJECT_TYPE_DEFINITION => $this->wrapper(
                    fn(InputObjectTypeDefinitionNode $node) =>
                        $inspection->visitInputObjectTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::ARGUMENT => $this->wrapper(
                    fn(ArgumentNode $argumentNode) =>
                        $inspection->visitArgumentNode($problemsHolder, $argumentNode),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::OBJECT_TYPE_DEFINITION => $this->wrapper(
                    fn(ObjectTypeDefinitionNode $node) =>
                        $inspection->visitObjectTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::LIST_TYPE => $this->wrapper(
                    fn(ListTypeNode $node, Node $parent, array $ancestors) =>
                        $inspection->visitListType($problemsHolder, $node, $parent, $ancestors),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::ENUM_TYPE_DEFINITION => $this->wrapper(
                    fn(EnumTypeDefinitionNode $node) =>
                    $inspection->visitEnumTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::SCALAR_TYPE_DEFINITION => $this->wrapper(
                    fn(ScalarTypeDefinitionNode $node) =>
                    $inspection->visitScalarTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::INPUT_VALUE_DEFINITION => $this->wrapper(
                    fn(InputValueDefinitionNode $node) =>
                    $inspection->visitInputValueDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::INTERFACE_TYPE_DEFINITION => $this->wrapper(
                    fn(InterfaceTypeDefinitionNode $node) =>
                    $inspection->visitInterfaceTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::UNION_TYPE_DEFINITION => $this->wrapper(
                    fn(UnionTypeDefinitionNode $node) =>
                    $inspection->visitUnionTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
                NodeKind::ENUM_VALUE_DEFINITION => $this->wrapper(
                    fn(EnumValueDefinitionNode $node) =>
                    $inspection->visitEnumValueDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                    $problemsHolder,
                ),
            ],
            [...$this->getInspections()],
        );

        return Visitor::visitInParallel($visitors);
    }

    private function wrapper(
        Closure $closure,
        Inspection $inspection,
        AffectedInspections $affectedInspections,
        ProblemsHolder $problemsHolder,
    ): Closure {
        return function (
            Node $node,
            $key,
            $parent,
            $path,
            array $ancestors,
        ) use (
            $closure,
            $affectedInspections,
            $inspection,
            $problemsHolder
        ) {
            $beforeProblems = count($problemsHolder->getProblems());

            if ($this->shouldSkip($node, $ancestors, $inspection)) {
                return;
            }

            $closure->call($this, $node, $parent, $ancestors);

            $afterProblems = count($problemsHolder->getProblems());

            // Check if inspection found any problems.
            if ($beforeProblems === $afterProblems) {
                return;
            }

            $affectedInspections->addInspection($inspection);
        };
    }

    /**
     * @param array<Node|NodeList> $parent
     */
    private function shouldSkip(Node $node, array $parent, Inspection $inspection): bool
    {
        foreach ($this->getSuppressors() as $suppressor) {
            if ($suppressor->shouldSuppress($node, $parent, $inspection)) {
                return true;
            }
        }
        return false;
    }
}
