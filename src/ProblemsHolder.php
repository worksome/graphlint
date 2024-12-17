<?php

declare(strict_types=1);

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
        Node $node,
        Fixer|null $fix = null,
    ): void {
        $this->problems[] = new ProblemDescriptor(
            $node,
            null,
            $fix
        );
    }

    public function registerProblemWithDescription(
        Node $node,
        string $description,
        Fixer|null $fix = null,
    ): void {
        $this->problems[] = new ProblemDescriptor(
            $node,
            $description,
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
