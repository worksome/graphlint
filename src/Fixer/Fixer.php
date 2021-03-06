<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixer;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\Visitor;
use Worksome\Graphlint\Analyser\AnalyserResult;
use Worksome\Graphlint\PostFixes\PostFixer;

class Fixer
{
    /**
     * @param PostFixer[] $postFixers
     */
    public function __construct(
        private array $postFixers,
    ) {
    }

    public function fix(AnalyserResult $result): FixerResult
    {
        $documentNode = $result->getDocumentNode();
        $problems = $result->getProblemsHolder()->getProblems();

        foreach ($problems as $problem) {
            $problem->getFix()?->fix($problem);
        }

        foreach ($this->postFixers as $postFixer) {
            /** @var DocumentNode $documentNode */
            $documentNode = Visitor::visit(
                $documentNode,
                [
                    'leave' => fn(Node $node, $tes, $tes2, $test3) => $postFixer->visitNode($node),
                ]
            );
        }

        return new FixerResult(
            $result->getOriginalDocumentNode(),
            $documentNode,
        );
    }
}
