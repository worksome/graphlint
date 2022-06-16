<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Inspections;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\Token;
use Illuminate\Support\Str;
use Worksome\Graphlint\Contracts\SuppressorInspection;
use Worksome\Graphlint\Fixes\CamelCaseNameFixer;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Utils\ApolloFederationChecker;
use Worksome\Graphlint\Utils\NodeNameResolver;

class IgnoreNextLineSuppressorInspection implements SuppressorInspection
{
    public function shouldSuppress(Node $node, array $parents, Inspection $inspection): bool
    {
        $previous = $node->loc->startToken->prev;

        if ($previous->kind !== Token::COMMENT) {
            return false;
        }

        return Str::of($previous->value)->trim()->is("@graphlint-ignore-next-line");
    }
}
