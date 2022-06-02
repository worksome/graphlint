<?php

namespace Worksome\Graphlint\Fixes;

use Worksome\Graphlint\ProblemDescriptor;

abstract class Fixer
{
    public abstract function fix(ProblemDescriptor $problemDescriptor): void;
}