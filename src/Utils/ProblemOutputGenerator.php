<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

use GraphQL\Language\Token;
use Worksome\Graphlint\ProblemDescriptor;
use Worksome\Graphlint\ShouldNotHappenException;

final readonly class ProblemOutputGenerator
{
    public function __construct(
        private ProblemDescriptor $descriptor,
        private int $index,
        private int $surroundingContextCharacters = 100,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function generate(): array
    {
        return [$this->title(), PHP_EOL, $this->schemaSnippet(), PHP_EOL];
    }

    private function title(): string
    {
        return sprintf(
            '<bg=red;options=bold>%s  %d: %s  %s</>',
            PHP_EOL . PHP_EOL,
            $this->index + 1,
            $this->descriptor->getDescription(),
            PHP_EOL
        );
    }

    private function schemaSnippet(): string
    {
        $bodyCharacters = mb_str_split($this->schemaBody());

        $bodyCharacters[$this->startToken()->start] = sprintf(
            '<fg=red>%s',
            $bodyCharacters[$this->startToken()->start]
        );
        $bodyCharacters[$this->endToken()->end] = sprintf('%s</>', $bodyCharacters[$this->endToken()->end]);

        $snippetStartIndex = max(0, $this->startToken()->start - $this->surroundingContextCharacters);
        $snippetLength = $this->endToken()->end - $snippetStartIndex + $this->surroundingContextCharacters;

        $snippetCharacters = array_splice($bodyCharacters, $snippetStartIndex, $snippetLength);

        return sprintf('<fg=black>%s</>', implode('', $snippetCharacters));
    }

    private function startToken(): Token
    {
        $startToken = $this->descriptor->getNode()->loc?->startToken;

        if ($startToken === null) {
            throw new ShouldNotHappenException('No location on node.');
        }

        return $startToken;
    }

    private function endToken(): Token
    {
        $endToken = $this->descriptor->getNode()->loc?->endToken;

        if ($endToken === null) {
            throw new ShouldNotHappenException('No location on node.');
        }

        return $endToken;
    }

    private function schemaBody(): string
    {
        $body = $this->descriptor->getNode()->loc?->source?->body;

        if ($body === null) {
            throw new ShouldNotHappenException('No source on node.');
        }

        return $body;
    }
}
