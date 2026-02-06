<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Type\Schema;
use Worksome\Graphlint\Analyser\Analyser;
use Worksome\Graphlint\Events\AfterAnalyseEvent;
use Worksome\Graphlint\Events\AfterFixerEvent;
use Worksome\Graphlint\Events\BeforeAnalyseEvent;
use Worksome\Graphlint\Events\EventInterface;
use Worksome\Graphlint\Fixer\Fixer;
use Worksome\Graphlint\Listeners\GraphlintListener;
use Worksome\Graphlint\Visitors\CompiledVisitorCollector;
use Worksome\Graphlint\Visitors\OriginalVisitorCollector;

class Graphlint
{
    /** @var GraphlintListener[] */
    private array $listeners;

    /**
     * @param iterable<GraphlintListener> $listeners
     */
    public function __construct(
        private readonly OriginalVisitorCollector $originalVisitorCollector,
        private readonly CompiledVisitorCollector $compiledVisitorCollector,
        private readonly Analyser $analyser,
        private readonly Fixer $fixer,
        iterable $listeners = [],
    ) {
        $this->listeners = [...$listeners];
    }

    public function inspect(
        DocumentNode $originalDocumentNode,
        DocumentNode $compiledDocumentNode,
        Schema|null $originalSchema = null,
        Schema|null $compiledSchema = null,
    ): void {
        $this->dispatchEvent(new BeforeAnalyseEvent());

        $originalAnalyserResult = $this->analyser->analyse(
            $originalDocumentNode,
            $this->originalVisitorCollector,
            $originalSchema,
        );
        $compiledAnalyserResult = $this->analyser->analyse(
            $compiledDocumentNode,
            $this->compiledVisitorCollector,
            $compiledSchema,
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
