<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Worksome\CodingStyle\WorksomeRectorConfig;

return static function (RectorConfig $rectorConfig): void {
    WorksomeRectorConfig::setup($rectorConfig);
    $rectorConfig->bootstrapFiles([]);

    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();

    // Define extra rule sets to be applied
    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::PHP_80,
        SetList::PHP_74,
        SetList::EARLY_RETURN,
        SetList::PSR_4,
    ]);

    // Register extra a single rules
    // $rectorConfig->rule(ClassOnObjectRector::class);
};
