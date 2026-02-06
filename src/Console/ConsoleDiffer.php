<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Console;

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

/**
 * Replacement for Symplify\PackageBuilder\Console\Output\ConsoleDiffer
 * using sebastian/diff which is already a dependency via PHPUnit/Pest.
 */
final class ConsoleDiffer
{
    private readonly Differ $differ;

    public function __construct()
    {
        $this->differ = new Differ(
            new UnifiedDiffOutputBuilder(
                header: "--- Original\n+++ New\n",
                addLineNumbers: true,
            )
        );
    }

    public function diff(string $old, string $new): string
    {
        $diff = $this->differ->diff($old, $new);

        return $this->colorize($diff);
    }

    private function colorize(string $diff): string
    {
        $lines = explode("\n", $diff);
        $coloredLines = [];

        foreach ($lines as $line) {
            if (str_starts_with($line, '+') && !str_starts_with($line, '+++')) {
                // Added lines in green
                $coloredLines[] = "<fg=green>{$line}</>";
            } elseif (str_starts_with($line, '-') && !str_starts_with($line, '---')) {
                // Removed lines in red
                $coloredLines[] = "<fg=red>{$line}</>";
            } elseif (str_starts_with($line, '@@')) {
                // Hunk headers in cyan
                $coloredLines[] = "<fg=cyan>{$line}</>";
            } else {
                $coloredLines[] = $line;
            }
        }

        return implode("\n", $coloredLines);
    }
}
