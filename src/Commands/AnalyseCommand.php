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
use Symfony\Component\Console\Input\InputOption;
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
    private const INPUT = 'input';

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
        $this->addOption(
            self::INPUT,
            null,
            InputOption::VALUE_OPTIONAL,
            "The format for the schema inputs",
            InputFormat::FILE->value,
        );
    }

    /**
     * @throws SyntaxError
     * @throws FilesystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle(
            $input,
            $output
        );

        $configurationFile = getcwd() . DIRECTORY_SEPARATOR . 'graphlint.php';

        if (! is_file($configurationFile) || ! is_readable($configurationFile)) {
            $style->error("Unable to find a \"graphlint.php\" configuration file in your current directory.");

            return self::FAILURE;
        }

        $kernel = new Kernel([
            $configurationFile,
        ]);
        $kernel->boot();

        $container = $kernel->getContainer();

        /** @var Analyser $analyser */
        $analyser = $container->get(Analyser::class);

        // Get the schema files
        /** @var string $compiledSchema */
        $compiledSchema = $input->getArgument(self::COMPILED_SCHEMA);
        /** @var string $inputFormat */
        $inputFormat = $input->getOption(self::INPUT);
        if (InputFormat::from($inputFormat) === InputFormat::FILE) {
            $rawSchema = file_get_contents(getcwd() . DIRECTORY_SEPARATOR . $compiledSchema);
        } else {
            $rawSchema = $compiledSchema;
        }

        $compiledNode = Parser::parse($rawSchema);
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
