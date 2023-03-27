<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixer;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\Visitor;
use Worksome\Graphlint\Analyser\AnalyserResult;
use Worksome\Graphlint\EmptyDocumentNode;
use Worksome\Graphlint\PostFixes\PostFixer;

class Fixer
{
    /**
     * @param PostFixer[] $postFixers
     */
    public function __construct(
        private readonly array $postFixers,
    ) {
    }

    public function fix(AnalyserResult $result): FixerResult
    {
        $documentNode = $result->getDocumentNode();
        $problems = $result->getProblemsHolder()->getProblems();

        if ($documentNode instanceof EmptyDocumentNode) {
            return new FixerResult(
                $documentNode,
                $documentNode,
            );
        }

        foreach ($problems as $problem) {
            $problem->getFix()?->fix($problem);
        }

        foreach ($this->postFixers as $postFixer) {
            /** @var DocumentNode $documentNode */
            $documentNode = Visitor::visit(
                $documentNode,
                /** @phpstan-ignore-next-line */
                [
                    'leave' => fn (Node $node) => $postFixer->visitNode($node),
                ],
            );
        }

        return new FixerResult(
            $result->getOriginalDocumentNode(),
            $documentNode,
        );
    }
}
