<?php

namespace Worksome\Graphlint;

use GraphQL\Language\AST\DocumentNode;
use Worksome\Graphlint\Analyser\Analyser;
use Worksome\Graphlint\Events\AfterAnalyseEvent;
use Worksome\Graphlint\Events\AfterFixerEvent;
use Worksome\Graphlint\Events\BeforeAnalyseEvent;
use Worksome\Graphlint\Events\EventInterface;
use Worksome\Graphlint\Fixer\Fixer;
use Worksome\Graphlint\Visitors\CompiledVisitorCollector;
use Worksome\Graphlint\Visitors\OriginalVisitorCollector;

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