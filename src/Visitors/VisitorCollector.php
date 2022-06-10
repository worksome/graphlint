<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Visitors;

use Closure;
use GraphQL\Language\AST\ArgumentNode;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Visitor;
use Worksome\Graphlint\Analyser\AffectedInspections;
use Worksome\Graphlint\Inspections\Inspection;
use Worksome\Graphlint\ProblemsHolder;

abstract class VisitorCollector
{
    /**
     * @return Inspection[]
     */
    public abstract function getInspections(): iterable;

    /**
     * @return array<string, callable>
     */
    public function getVisitor(ProblemsHolder $problemsHolder, AffectedInspections $affectedInspections): array
    {
        $visitors = array_map(
            fn(Inspection $inspection) => [
                NodeKind::FIELD_DEFINITION => $this->wrapper(
                    fn(FieldDefinitionNode $fieldDefinitionNode) =>
                        $inspection->visitFieldDefinition($problemsHolder, $fieldDefinitionNode),
                    $inspection,
                    $affectedInspections,
                ),
                NodeKind::INPUT_OBJECT_TYPE_DEFINITION => $this->wrapper(
                    fn(InputObjectTypeDefinitionNode $node) =>
                        $inspection->visitInputObjectTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                ),
                NodeKind::ARGUMENT => $this->wrapper(
                    fn(ArgumentNode $argumentNode) =>
                        $inspection->visitArgumentNode($problemsHolder, $argumentNode),
                    $inspection,
                    $affectedInspections,
                ),
                NodeKind::OBJECT_TYPE_DEFINITION => $this->wrapper(
                    fn(ObjectTypeDefinitionNode $node) =>
                        $inspection->visitObjectTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                ),
                NodeKind::LIST_TYPE => $this->wrapper(
                    fn(ListTypeNode $node, Node $parent) =>
                    $inspection->visitListType($problemsHolder, $node, $parent),
                    $inspection,
                    $affectedInspections,
                ),
                NodeKind::ENUM_TYPE_DEFINITION => $this->wrapper(
                    fn(EnumTypeDefinitionNode $node) =>
                    $inspection->visitEnumTypeDefinition($problemsHolder, $node),
                    $inspection,
                    $affectedInspections,
                ),
            ],
            [...$this->getInspections()],
        );

        return Visitor::visitInParallel($visitors);
    }

    private function wrapper(
        Closure $closure,
        Inspection $inspection,
        AffectedInspections $affectedInspections
    ): Closure {
        return function (Node $node, $key, $parent) use ($closure, $affectedInspections, $inspection) {

            $beforeNode = $node->toArray(true);

            $closure->call($this, $node, $parent);

            if ($beforeNode != $node->toArray(true)) {
                $affectedInspections->addInspection($inspection);
            }
        };
    }
}
