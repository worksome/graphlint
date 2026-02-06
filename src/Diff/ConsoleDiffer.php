<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Diff;

use SebastianBergmann\Diff\Differ;

readonly class ConsoleDiffer
{
    public function __construct(
        private Differ $differ,
        private ColorConsoleDiffFormatter $colorConsoleDiffFormatter,
    ) {
    }

    public function diff(string $old, string $new): string
    {
        return $this->colorConsoleDiffFormatter->format(
            $this->differ->diff($old, $new)
        );
    }
}
