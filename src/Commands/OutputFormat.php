<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Commands;

enum OutputFormat: string
{
    case Text = 'text';
    case Checkstyle = 'checkstyle';
}
