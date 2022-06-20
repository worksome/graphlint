<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Analyser;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Printer;
use GraphQL\Language\Visitor;
use GraphQL\Utils\BuildSchema;
use Worksome\Graphlint\EmptyDocumentNode;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Visitors\VisitorCollector;

class Analyser
{
    public function analyse(
        DocumentNode $documentNode,
        VisitorCollector $visitorCollector,
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

        Visitor::visit(
            $documentNode,
            $visitorCollector->getVisitor(
                $problemsHolder,
                $affectedInspections,
            )
        );

        return new AnalyserResult(
            $originalDocumentNode,
            $documentNode,
            $problemsHolder,
            $affectedInspections,
        );
    }
}
