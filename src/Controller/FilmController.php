<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class FilmController extends AbstractController
{
    /** @var KernelInterface $appKernel */
    private $appKernel;
    public function __construct(
      KernelInterface $appKernel
    ){
       $this->appKernel=$appKernel;
    }
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('films/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/films/liste", name="liste_films")
     */
    public function liste(): Response
    {
        try {
            $projectRoot=$this->appKernel->getProjectDir();
            $html=$projectRoot.'/config/packages/movies/movies.xml';

            $crawler = new Crawler();
            $crawler->addXmlContent(file_get_contents($html));

            foreach ($crawler->filter('movies > movie') as $key=> $item) {
                dump($item->nodeValue);
            }
            dd('landry');
        } catch (ParseException $exception) {
            var_dump('Unable to parse the YAML string: %s', $exception->getMessage()."--".$exception->getFile()."".$exception->getLine());
        }
        return $this->render('marque/liste_marque.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/film/details/show/{id}", name="app_film_detail_show")
     */
    public function showFilmId($id): Response
    {
        return $this->render('films/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
