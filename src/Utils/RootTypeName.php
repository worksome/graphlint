<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

enum RootTypeName: string
{
    case Query = 'Query';
    case Mutation = 'Mutation';
}
