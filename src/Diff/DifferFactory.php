<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Diff;

use SebastianBergmann\Diff\Differ;

final class DifferFactory
{
    /** @api */
    public function create(): Differ
    {
        $completeUnifiedDiffOutputBuilderFactory = new CompleteUnifiedDiffOutputBuilderFactory();

        $unifiedDiffOutputBuilder = $completeUnifiedDiffOutputBuilderFactory->create();

        return new Differ($unifiedDiffOutputBuilder);
    }
}
