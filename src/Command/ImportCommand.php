<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName='impport:bdd-test';

    protected function configure()
    {
        // Name and description for app/console command
        $this
            ->setName('import:bdd')
            ->setDescription('Import data in db sqlite');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'var/data.db';

        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.toarray');
        $data = $converter->convert($fileName, ';');

        return $data;
    }


}
