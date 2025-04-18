<?php

declare(strict_types=1);

namespace Worksome\Graphlint\AutowireArrayParameter\DocBlock;

use Nette\Utils\Strings;

final class ParamTypeDocBlockResolver
{
    private const TYPE_PART = 'type';

    /**
     * Copied mostly from
     * https://github.com/nette/di/blob/d1c0598fdecef6d3b01e2ace5f2c30214b3108e6/src/DI/Autowiring.php#L215
     *
     * @see https://regex101.com/r/wGteeZ/1
     */
    private const NORMAL_REGEX = '#@param\s+(?<' . self::TYPE_PART . '>[\w\\\\]+)\[\]\s+\$' . self::NAME_PLACEHOLDER . '#';

    private const SHAPE_REGEX = '#@param\s+(array|iterable)\<(?<' . self::TYPE_PART . '>[\w\\\\]+)\>\s+\$' . self::NAME_PLACEHOLDER . '#';

    private const NAME_PLACEHOLDER = '__NAME__';

    private const ARRAY_REGEXES = [self::NORMAL_REGEX, self::SHAPE_REGEX];

    public function resolve(string $docBlock, string $parameterName): string|null
    {
        foreach (self::ARRAY_REGEXES as $arrayRegexWithPlaceholder) {
            $arrayRegex = str_replace(self::NAME_PLACEHOLDER, $parameterName, $arrayRegexWithPlaceholder);

            $result = Strings::match($docBlock, $arrayRegex);
            if (isset($result[self::TYPE_PART])) {
                return $result[self::TYPE_PART];
            }
        }

        return null;
    }
}
