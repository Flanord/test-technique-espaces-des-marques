<?php

namespace App\Command;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

class ImportCommand extends Command
{

    protected static $defaultName='app:create-movies';

    /** @var KernelInterface $appKernel */
    private $appKernel;
    private $entityManager;

    public function __construct(
        KernelInterface $appKernel,
        EntityManagerInterface $entityManager
    )
    {
        $this->appKernel = $appKernel;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

  /*  protected function configure()
    {
        // Name and description for app/console command
        $this
            ->setDescription('Import data in db sqlite')
            ->addArgument('id',InputArgument::REQUIRED,'id movie')
            ->addArgument('title',InputArgument::REQUIRED,'title movie')
            ->addArgument('genre',InputArgument::REQUIRED,'genre movie')
            ->addArgument('description',InputArgument::REQUIRED,'description movie')
            ->addArgument('director',InputArgument::REQUIRED,'director movie')
            ->addArgument('year',InputArgument::REQUIRED,'year movie')
            ->addArgument('runtime',InputArgument::REQUIRED,'runtime movie')
            ->addArgument('rate',InputArgument::REQUIRED,'runtime movie');

    }*/

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(['Commande create movie','================']);

        $projectRoot = $this->appKernel->getProjectDir();
        $html = $projectRoot . '/config/packages/movies/movies.xml';
        $xml = simplexml_load_file($html);
        foreach ($xml as $key => $item) {
            $movie = new Movie();
            $movie->setTitle($item->title);
            $movie->setGenre($item->genre);
            $movie->setDescription($item->description);
            $movie->setDirector($item->director);
            $movie->setYear($item->year);
            $movie->setRuntime($item->runtime);
            $movie->setRate($item->rate);
            $this->entityManager->persist($movie);
        }
        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);

        $io->success('Votre commande est excuté avec  succès');

        return Command::SUCCESS;
    }
}
