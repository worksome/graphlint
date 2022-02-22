<?php

namespace Olivernybroe\Graphlint;

use Olivernybroe\Graphlint\Events\AfterAnalyseEvent;
use Olivernybroe\Graphlint\Events\AfterFixerEvent;
use Olivernybroe\Graphlint\Events\BeforeAnalyseEvent;

interface GraphlintListener
{
    public function beforeAnalyse(BeforeAnalyseEvent $event): void;
    public function afterAnalyse(AfterAnalyseEvent $event): void;
    public function afterFixer(AfterFixerEvent $event): void;
}