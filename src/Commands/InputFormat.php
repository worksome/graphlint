<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Commands;

enum InputFormat: string
{
    case FILE = "file";
    case TEXT = "text";
}
