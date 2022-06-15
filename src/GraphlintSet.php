<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

enum GraphlintSet: string
{
    case Standard = __DIR__ . '/../config/Sets/standard.php';
}
