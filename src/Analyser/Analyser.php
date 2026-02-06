<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Analyser;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Printer;
use GraphQL\Language\Visitor;
use GraphQL\Type\Schema;
use GraphQL\Utils\TypeInfo;
use Worksome\Graphlint\EmptyDocumentNode;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Visitors\VisitorCollector;

class Analyser
{
    public function analyse(
        DocumentNode $documentNode,
        VisitorCollector $visitorCollector,
        Schema|null $schema = null,
    ): AnalyserResult {
        $problemsHolder = new ProblemsHolder();
        $affectedInspections = new AffectedInspections();

        if ($documentNode instanceof EmptyDocumentNode) {
            return new AnalyserResult(
                $documentNode,
                $documentNode,
                $problemsHolder,
                $affectedInspections,
            );
        }

        $originalDocumentNode = Parser::parse(
            Printer::doPrint($documentNode)
        );

        $typeInfo = $schema !== null ? new TypeInfo($schema) : null;
        $visitor = $visitorCollector->getVisitor(
            $problemsHolder,
            $affectedInspections,
            $typeInfo,
        );

        if ($typeInfo !== null) {
            $visitor = Visitor::visitWithTypeInfo($typeInfo, $visitor);
        }

        Visitor::visit($documentNode, $visitor);

        return new AnalyserResult(
            $originalDocumentNode,
            $documentNode,
            $problemsHolder,
            $affectedInspections,
        );
    }
}
