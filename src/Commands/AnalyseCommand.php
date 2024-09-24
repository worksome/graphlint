<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Commands;

use GraphQL\Error\SyntaxError;
use GraphQL\Language\Parser;
use JsonException;
use Safe\Exceptions\FilesystemException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Path;
use Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use Worksome\Graphlint\EmptyDocumentNode;
use Worksome\Graphlint\Graphlint;
use Worksome\Graphlint\Kernel;
use Worksome\Graphlint\Listeners\CheckstyleListener;
use Worksome\Graphlint\Listeners\ConsolePrinterListener;
use Worksome\Graphlint\ShouldNotHappenException;

use function Safe\file_get_contents;

class AnalyseCommand extends Command
{
    private const ORIGINAL_SCHEMA = 'sdl';
    private const COMPILED_SCHEMA = 'compiled_schema';
    private const INPUT = 'input';
    private const FORMAT = 'format';

    protected static $defaultName = 'analyse';

    protected static $defaultDescription = 'Analyse a GraphQL file';

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
            InputOption::VALUE_REQUIRED,
            'The format for the schema inputs.',
            InputFormat::FILE->value,
        );
        $this->addOption(
            self::FORMAT,
            null,
            InputOption::VALUE_REQUIRED,
            'The output format.',
            OutputFormat::Text->value,
        );
        $this->addOption(
            'configuration',
            'c',
            InputOption::VALUE_REQUIRED,
            'The configuration file.',
            'graphlint.php',
        );
    }

    /** @throws SyntaxError|FilesystemException|JsonException */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle(
            $input,
            $output
        );

        $currentWorkingDirectory = getcwd();
        if ($currentWorkingDirectory === false) {
            throw new ShouldNotHappenException();
        }

        /** @var string $configurationFile */
        $configurationFile = $input->getOption('configuration');

        /** @var non-empty-string $configurationFile */
        $configurationFile = Path::makeAbsolute($configurationFile, $currentWorkingDirectory);

        if (! is_file($configurationFile) || ! is_readable($configurationFile)) {
            $style->error("Unable to find a \"{$configurationFile}\" configuration file.");

            return self::FAILURE;
        }

        $kernel = new Kernel([
            $configurationFile,
        ]);
        $kernel->boot();

        $container = $kernel->getContainer();

        /** @var Graphlint $graphlint */
        $graphlint = $container->get(Graphlint::class);
        /** @var ConsoleDiffer $differ */
        $differ = $container->get(ConsoleDiffer::class);

        // Get the schema files
        /** @var string $compiledSchema */
        $compiledSchema = $input->getArgument(self::COMPILED_SCHEMA);
        /** @var string $inputFormat */
        $inputFormat = $input->getOption(self::INPUT);
        $rawSchema = match ($inputFormat = InputFormat::tryFrom($inputFormat)) {
            InputFormat::FILE => file_get_contents(getcwd() . DIRECTORY_SEPARATOR . $compiledSchema),
            default => $compiledSchema,
        };

        $compiledNode = Parser::parse($rawSchema);

        $compiledPath = $inputFormat === InputFormat::FILE ? getcwd() . DIRECTORY_SEPARATOR . $compiledSchema : null;

        /** @var string $format */
        $format = $input->getOption(self::FORMAT);
        $printer = match (OutputFormat::tryFrom($format)) {
            OutputFormat::Checkstyle => new CheckstyleListener($style, $compiledPath),
            default => new ConsolePrinterListener($style, $differ),
        };

        $graphlint->addListener($printer);

        $graphlint->inspect(
            new EmptyDocumentNode(),
            $compiledNode,
        );

        return $printer->getStatusCode();
    }
}
