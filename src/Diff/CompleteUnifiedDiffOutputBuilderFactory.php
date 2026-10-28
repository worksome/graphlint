<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Diff;

use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

/** @api */
final class CompleteUnifiedDiffOutputBuilderFactory
{
    /** @api */
    public function create(): StrictUnifiedDiffOutputBuilder
    {
        return new StrictUnifiedDiffOutputBuilder([
            'addLineNumbers' => false,
            'contextLines' => 10000,
            'header' => '',
        ]);
    }
}
