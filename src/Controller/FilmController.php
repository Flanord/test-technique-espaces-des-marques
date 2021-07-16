<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FilmController extends AbstractController
{
    /** @var KernelInterface $appKernel */
    private $appKernel;
    private $entityManager;

    public function __construct(
        KernelInterface $appKernel,
        EntityManagerInterface $entityManager
    ){
        $this->appKernel = $appKernel;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="liste_films")
     */
    public function liste(): Response
    {
        $movies= $this->entityManager->getRepository(Movie::class)->findAll();
        return $this->render('films/index.html.twig', [
            'movies' => $movies,
        ]);
    }


    /**
     * @Route("/film/details/show/{id}", name="app_film_detail_show")
     */
    public function showFilmId($id)
    {
        if ($id) {
            $film = $this->entityManager->getRepository(Movie::class)->find($id);
        return $this->render('films/details_film.html.twig', [
            'film'=>$film,
        ]);
        }else{
            throw $this->createNotFoundException('Ce film  n\'existe pas ' . $id);
        }

    }

    /**
     * @Route("/film/xml", name="app_film_xml")
     */
    public function  getXml():Response
    {
        $serializer = new Serializer(
                [new ObjectNormalizer()],
                [new XmlEncoder()]
            );
             $projectRoot = $this->appKernel->getProjectDir();
            $html = $projectRoot . '/config/packages/movies/movies.xml';
            $data = simplexml_load_file($html);
            $movies = [];
            foreach ($data as $value) {
                $movies[] = $serializer
                    ->deserialize($value->asXML(), Movie::class, 'xml');
            }

            dd($movies);
    }




}
