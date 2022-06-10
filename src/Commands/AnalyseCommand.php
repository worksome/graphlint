<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Commands;

use GraphQL\Error\SyntaxError;
use GraphQL\Language\Parser;
use GraphQL\Language\Printer;
use Safe\Exceptions\FilesystemException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use Worksome\Graphlint\Analyser\Analyser;
use Worksome\Graphlint\Kernel;
use Worksome\Graphlint\Visitors\CompiledVisitorCollector;

use function Safe\file_get_contents;

class AnalyseCommand extends Command
{
    private const ORIGINAL_SCHEMA = 'sdl';
    private const COMPILED_SCHEMA = 'compiled_schema';

    protected static $defaultName = 'analyse';

    protected static $defaultDescription = 'Analyses a graphql file';

    protected function configure(): void
    {
        $this->addArgument(
            self::COMPILED_SCHEMA,
            InputArgument::REQUIRED,
            'The compiled schema definition language.'
        );
        $this->addArgument(
            self::ORIGINAL_SCHEMA,
            InputArgument::OPTIONAL,
            'The original schema definition language.'
        );
    }

    /**
     * @throws SyntaxError
     * @throws FilesystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configurationFile = getcwd() . DIRECTORY_SEPARATOR . 'graphlint.php';

        $kernel = new Kernel([
            $configurationFile,
        ]);
        $kernel->boot();

        $container = $kernel->getContainer();

        $style = new SymfonyStyle(
            $input,
            $output
        );

        /** @var Analyser $analyser */
        $analyser = $container->get(Analyser::class);

        // get the schema files
        $input->getArgument(self::ORIGINAL_SCHEMA);
        $compiledSchema = $input->getArgument(self::COMPILED_SCHEMA);

        $compiledNode = Parser::parse(file_get_contents(getcwd() . DIRECTORY_SEPARATOR . $compiledSchema));
        /** @var CompiledVisitorCollector $compiledVisitor */
        $compiledVisitor = $container->get(CompiledVisitorCollector::class);


        $style->info("Analysing schema...");
        $result = $analyser->analyse(
            $compiledNode,
            $compiledVisitor
        );

        $problems = $result->getProblemsHolder()->getProblems();
        $problemCount = count($problems);

        if ($problemCount === 0) {
            $style->success("No problems found!");
            return self::SUCCESS;
        }

        $style->error("Found $problemCount problems");

        // Print out which inspections affected the schema.
        $style->writeln('<options=underscore>Applied inspections:</>');
        $style->listing($result->getAffectedInspections()->getInspections());

        foreach ($problems as $problemDescriptor) {
            $problemDescriptor->getFix()?->fix($problemDescriptor);
        }

        $changedNode = Printer::doPrint($result->getDocumentNode());
        $originalNode = Printer::doPrint($result->getOriginalDocumentNode());

        /** @var ConsoleDiffer $differ */
        $differ = $container->get(ConsoleDiffer::class);
        $style->writeln($differ->diff(
            $originalNode,
            $changedNode,
        ));

        return self::FAILURE;
    }
}
