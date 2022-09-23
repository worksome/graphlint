<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Listeners;

use DOMDocument;
use DOMElement;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Worksome\Graphlint\Analyser\AnalyserResult;
use Worksome\Graphlint\Events\AfterAnalyseEvent;
use Worksome\Graphlint\Events\AfterFixerEvent;
use Worksome\Graphlint\Events\BeforeAnalyseEvent;
use Worksome\Graphlint\ProblemDescriptor;
use Worksome\Graphlint\ShouldNotHappenException;

class CheckstyleListener implements GraphlintListener
{
    private bool $hasErrors = false;

    public function __construct(
        private readonly SymfonyStyle $style,
        private readonly string|null $compiledPath = null
    ) {
        if (! \extension_loaded('dom')) {
            throw new RuntimeException('Cannot generate report! `ext-dom` is not available!');
        }
    }

    public function beforeAnalyse(BeforeAnalyseEvent $event): void
    {
    }

    public function afterAnalyse(AfterAnalyseEvent $event): void
    {
        $compiledResult = $event->getCompiledAnalyserResult();
        $originalResult = $event->getOriginalAnalyserResult();

        $dom = new DOMDocument('1.0', 'UTF-8');
        $checkstyles = $dom->appendChild($dom->createElement('checkstyle'));

        foreach (['Compiled' => $compiledResult, 'Original' => $originalResult] as $type => $result) {
            /** @var AnalyserResult $result */

            $problems = $result->getProblemsHolder()->getProblems();
            $problemCount = \count($problems);

            if ($problemCount === 0) {
                continue;
            }

            $this->hasErrors = true;

            foreach ($problems as $problem) {
                $location = $this->compiledPath ?? $problem->getNode()->loc?->source?->name;

                if ($location === null) {
                    throw new ShouldNotHappenException("No location on node.");
                }

                /** @var DOMElement $file */
                $file = $checkstyles->appendChild($dom->createElement('file'));
                $file->setAttribute('name', $location);

                $error = $this->createError($dom, $problem);
                $file->appendChild($error);
            }
        }

        $dom->formatOutput = true;

        $this->style->writeln((string) $dom->saveXML());
    }

    public function afterFixer(AfterFixerEvent $event): void
    {
    }

    private function createError(DOMDocument $dom, ProblemDescriptor $problem): DOMElement
    {
        $error = $dom->createElement('error');
        $error->setAttribute('severity', $problem->getFix() === null ? 'error' : 'warning');
        $error->setAttribute('source', 'Graphlint: ' . $problem->getDescription());
        $error->setAttribute('message', 'Found violation(s) of type: ' . $problem->getDescription());

        if ($line = $problem->getNode()->loc?->startToken?->line) {
            $error->setAttribute('line', (string) $line);
        }

        return $error;
    }

    public function getStatusCode(): int
    {
        return $this->hasErrors ? Command::FAILURE : Command::SUCCESS;
    }
}
