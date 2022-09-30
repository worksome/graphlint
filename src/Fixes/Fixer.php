<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Fixes;

use Worksome\Graphlint\ProblemDescriptor;

abstract class Fixer
{
    abstract public function fix(ProblemDescriptor $problemDescriptor): void;
}
