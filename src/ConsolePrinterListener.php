<?php

namespace Olivernybroe\Graphlint;

use GraphQL\Language\Printer;
use Olivernybroe\Graphlint\Events\AfterAnalyseEvent;
use Olivernybroe\Graphlint\Events\AfterFixerEvent;
use Olivernybroe\Graphlint\Events\BeforeAnalyseEvent;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\ConsoleColorDiff\Console\Output\ConsoleDiffer;

class ConsolePrinterListener implements GraphlintListener
{
    private bool $hasErrors = false;

    public function __construct(
        private SymfonyStyle $style,
        private ConsoleDiffer $consoleDiffer,
    ) {}

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
            $problemsAmount = count($problems);

            if ($problemsAmount === 0) {
                $this->style->success("No problems found in $type schema!");
                continue;
            }

            $this->hasErrors = true;
            $this->style->error("Found $problemsAmount problems in $type schema");
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