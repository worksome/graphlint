<?php

namespace Olivernybroe\Graphlint\Fixes;

use Olivernybroe\Graphlint\ProblemDescriptor;

abstract class Fixer
{
    public abstract function fix(ProblemDescriptor $problemDescriptor): void;
}