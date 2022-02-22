<?php

namespace Olivernybroe\Graphlint;

use GraphQL\Language\AST\DocumentNode;
use Olivernybroe\Graphlint\Analyser\Analyser;
use Olivernybroe\Graphlint\Events\AfterAnalyseEvent;
use Olivernybroe\Graphlint\Events\AfterFixerEvent;
use Olivernybroe\Graphlint\Events\BeforeAnalyseEvent;
use Olivernybroe\Graphlint\Events\EventInterface;
use Olivernybroe\Graphlint\Fixer\Fixer;
use Olivernybroe\Graphlint\Visitors\CompiledVisitorCollector;
use Olivernybroe\Graphlint\Visitors\OriginalVisitorCollector;

class Graphlint
{
    /**
     * @param GraphlintListener[] $listeners
     */
    public function __construct(
        private OriginalVisitorCollector $originalVisitorCollector,
        private CompiledVisitorCollector $compiledVisitorCollector,
        private Analyser $analyser,
        private Fixer $fixer,
        private array $listeners,
    ) {}

    public function inspect(
        DocumentNode $originalDocumentNode,
        DocumentNode $compiledDocumentNode,
    ) {
        $this->dispatchEvent(new BeforeAnalyseEvent());

        $originalAnalyserResult = $this->analyser->analyse(
            $originalDocumentNode,
            $this->originalVisitorCollector
        );
        $compiledAnalyserResult = $this->analyser->analyse(
            $compiledDocumentNode,
            $this->compiledVisitorCollector
        );

        $this->dispatchEvent(new AfterAnalyseEvent(
            $originalAnalyserResult,
            $compiledAnalyserResult,
        ));

        $originalFixerResult = $this->fixer->fix($originalAnalyserResult);
        $compiledFixerResult = $this->fixer->fix($compiledAnalyserResult);

        $this->dispatchEvent(new AfterFixerEvent(
            $originalFixerResult,
            $compiledFixerResult,
        ));
    }

    private function dispatchEvent(EventInterface $event): void
    {
        foreach ($this->listeners as $listener) {
            if ($event instanceof BeforeAnalyseEvent) {
                $listener->beforeAnalyse($event);
            } elseif ($event instanceof AfterAnalyseEvent) {
                $listener->afterAnalyse($event);
            } elseif ($event instanceof AfterFixerEvent) {
                $listener->afterFixer($event);
            }
        }
    }

    public function addListener(GraphlintListener $listener): self
    {
        $this->listeners[] = $listener;
        return $this;
    }
}