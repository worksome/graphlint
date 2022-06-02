<?php

namespace Worksome\Graphlint;

use GraphQL\Language\AST\Node;
use Worksome\Graphlint\Fixes\Fixer;

class ProblemsHolder
{
    /**
     * @var ProblemDescriptor[]
     */
    private array $problems = [];

    public function registerProblem(
        Node   $node,
        ?Fixer $fix = null,
    ): void
    {
        $this->problems[] = new ProblemDescriptor(
            $node,
            $fix
        );
    }

    /**
     * @return ProblemDescriptor[]
     */
    public function getProblems(): array
    {
        return $this->problems;
    }
}