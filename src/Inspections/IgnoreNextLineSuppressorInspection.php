<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\Token;
use Illuminate\Support\Str;
use Worksome\Graphlint\Contracts\SuppressorInspection;

class IgnoreNextLineSuppressorInspection implements SuppressorInspection
{
    public function shouldSuppress(
        Node $node,
        Node|NodeList|null $parent,
        array $ancestors,
        Inspection $inspection,
    ): bool {
        $previous = $node->loc?->startToken?->prev;
        if ($previous === null) {
            return false;
        }
        if ($previous->kind !== Token::COMMENT) {
            return false;
        }
        $comment = $previous->value;

        if ($comment === null) {
            return false;
        }

        return Str::of($comment)->trim()->is('@graphlint-ignore-next-line');
    }
}
