<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Listeners;

use Worksome\Graphlint\Events\AfterAnalyseEvent;
use Worksome\Graphlint\Events\AfterFixerEvent;
use Worksome\Graphlint\Events\BeforeAnalyseEvent;

interface GraphlintListener
{
    public function beforeAnalyse(BeforeAnalyseEvent $event): void;

    public function afterAnalyse(AfterAnalyseEvent $event): void;

    public function afterFixer(AfterFixerEvent $event): void;
}
