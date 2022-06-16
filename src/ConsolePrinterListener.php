<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use GraphQL\Language\Printer;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use Worksome\Graphlint\Analyser\AnalyserResult;
use Worksome\Graphlint\Events\AfterAnalyseEvent;
use Worksome\Graphlint\Events\AfterFixerEvent;
use Worksome\Graphlint\Events\BeforeAnalyseEvent;

class ConsolePrinterListener implements GraphlintListener
{
    private bool $hasErrors = false;

    public function __construct(
        private readonly SymfonyStyle $style,
        private readonly ConsoleDiffer $consoleDiffer,
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
            /** @var AnalyserResult $result */

            $problems = $result->getProblemsHolder()->getProblems();
            $problemCount = count($problems);

            if ($problemCount === 0) {
                $this->style->success("No problems found in $type schema!");
                continue;
            }

            $this->hasErrors = true;
            $this->style->error("Found $problemCount problems in $type schema");

            $unfixableErrors = Collection::make($problems)
                ->filter(fn(ProblemDescriptor $descriptor) => $descriptor->getFix() === null)
                ->count();
            $this->style->warning("$unfixableErrors cannot be automatically fixed in $type schema");

            $this->style->table(
                [
                    'location',
                    'description',
                ],
                Collection::make($problems)
                    ->map(function (ProblemDescriptor $descriptor) {
                        $startToken = $descriptor->getNode()->loc?->startToken;
                        if ($startToken === null) {
                            throw new ShouldNotHappenException("No location on node.");
                        }

                        return ["$startToken->line:$startToken->column", $descriptor->getDescription()];
                    })->all()
            );
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
        return $this->hasErrors ? Command::FAILURE : Command::SUCCESS;
    }
}
