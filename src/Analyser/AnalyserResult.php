<?php

namespace Olivernybroe\Graphlint\Analyser;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Printer;
use Olivernybroe\Graphlint\ProblemsHolder;
use SebastianBergmann\Diff\Differ;
use Symplify\ConsoleColorDiff\Console\Output\ConsoleDiffer;

class AnalyserResult
{
    public function __construct(
        private DocumentNode $originalDocumentNode,
        private DocumentNode $documentNode,
        private ProblemsHolder $problemsHolder,
        private AffectedInspections $affectedInspections,
    ) {}

    public function getAffectedInspections(): AffectedInspections
    {
        return $this->affectedInspections;
    }

    public function getProblemsHolder(): ProblemsHolder
    {
        return $this->problemsHolder;
    }

    public function getDocumentNode(): DocumentNode
    {
        return $this->documentNode;
    }

    public function getOriginalDocumentNode(): DocumentNode
    {
        return $this->originalDocumentNode;
    }
}