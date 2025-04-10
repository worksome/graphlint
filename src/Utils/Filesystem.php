<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Utils;

use ErrorException;

/**
 * This contains helper methods from Safe PHP.
 * @deprecated This should be removed when updating to Safe PHP 3.x
 */
final class Filesystem
{
    public static function file_get_contents(
        string $filename,
        bool $use_include_path = false,
        mixed $context = null,
        int $offset = 0,
        int|null $length = null
    ): string {
        error_clear_last();
        if ($length !== null) {
            // @phpstan-ignore-next-line
            $safeResult = \file_get_contents($filename, $use_include_path, $context, $offset, $length);
        } elseif ($offset !== 0) {
            // @phpstan-ignore-next-line
            $safeResult = \file_get_contents($filename, $use_include_path, $context, $offset);
        } elseif ($context !== null) {
            // @phpstan-ignore-next-line
            $safeResult = \file_get_contents($filename, $use_include_path, $context);
        } else {
            $safeResult = \file_get_contents($filename, $use_include_path);
        }
        if ($safeResult === false) {
            throw self::exceptionFromPhpError();
        }
        return $safeResult;
    }

    public static function getcwd(): string
    {
        error_clear_last();
        $safeResult = \getcwd();
        if ($safeResult === false) {
            throw self::exceptionFromPhpError();
        }
        return $safeResult;
    }

    private static function exceptionFromPhpError(): ErrorException
    {
        $error = error_get_last();

        return new ErrorException($error['message'] ?? 'An error occured', 0, $error['type'] ?? 1);
    }
}
