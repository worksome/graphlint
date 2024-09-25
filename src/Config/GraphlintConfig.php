<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Config;

use Worksome\Graphlint\Configuration\GraphlintConfigBuilder;

final class GraphlintConfig
{
    public static function configure(): GraphlintConfigBuilder
    {
        return new GraphlintConfigBuilder();
    }
}
