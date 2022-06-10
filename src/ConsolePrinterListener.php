<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use GraphQL\Language\Printer;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use Worksome\Graphlint\Events\AfterAnalyseEvent;
use Worksome\Graphlint\Events\AfterFixerEvent;
use Worksome\Graphlint\Events\BeforeAnalyseEvent;

class ConsolePrinterListener implements GraphlintListener
{
    private bool $hasErrors = false;

    public function __construct(
        private SymfonyStyle $style,
        private ConsoleDiffer $consoleDiffer,
    ) {
    }

    public function beforeAnalyse(BeforeAnalyseEvent $event): void
    {
        $this->style->info("Analysing schema...");
    }

    public function afterAnalyse(AfterAnalyseEvent $event): void
    {
        $compiledResult = $event->getCompiledAnalyserResult();
        $originalResult = $event->getOriginalAnalyserResult();

        foreach (['compiled' => $compiledResult, 'original' => $originalResult] as $type => $result) {
            $problems = $result->getProblemsHolder()->getProblems();
            $problemCount = count($problems);

            if ($problemCount === 0) {
                $this->style->success("No problems found in $type schema!");
                continue;
            }

            $this->hasErrors = true;
            $this->style->error("Found $problemCount problems in $type schema");
        }
    }

    public function afterFixer(AfterFixerEvent $event): void
    {
        $originalFixerResult = $event->getOriginalFixerResult();
        $compiledFixerResult = $event->getCompiledFixerResult();

        foreach ([$compiledFixerResult, $originalFixerResult] as $result) {
            $changedNode = Printer::doPrint($result->getDocumentNode());
            $originalNode = Printer::doPrint($result->getOriginalDocumentNode());

            if ($changedNode === $originalNode) {
                continue;
            }

            $this->style->writeln($this->consoleDiffer->diff(
                $originalNode,
                $changedNode,
            ));
        }
    }

    public function getStatusCode(): int
    {
        return $this->hasErrors ? 0 : 1;
    }
}
