<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Diff;

use ReflectionProperty;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

/** @api */
final class CompleteUnifiedDiffOutputBuilderFactory
{
    /** @api */
    public function create(): UnifiedDiffOutputBuilder
    {
        $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder('');

        $contextLines = new ReflectionProperty($unifiedDiffOutputBuilder, 'contextLines');

        $contextLines->setValue($unifiedDiffOutputBuilder, 10000);

        return $unifiedDiffOutputBuilder;
    }
}
