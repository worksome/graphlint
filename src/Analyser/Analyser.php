<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Analyser;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Printer;
use GraphQL\Language\Visitor;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Visitors\VisitorCollector;

class Analyser
{
    public function analyse(
        DocumentNode $documentNode,
        VisitorCollector $visitorCollector,
    ): AnalyserResult {
        $originalDocumentNode = Parser::parse(
            Printer::doPrint($documentNode)
        );

        $problemsHolder = new ProblemsHolder();
        $affectedInspections = new AffectedInspections();

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
