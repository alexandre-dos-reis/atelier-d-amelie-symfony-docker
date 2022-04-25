<?php

namespace App\Controller;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RealisationController extends AbstractController
{
    /**
     * @Route("/realisation", name="realisation")
     */
    public function index(): Response
    {
        $realisations = (new Finder)
            ->files()
            ->in('static/realisation')
            ->name('*.jpg')
            ->sortByName();

        $infos = Yaml::parseFile('static/realisation/info.yaml')['titles'];

        return $this->render('realisation/index.html.twig', [
            'realisations' => $realisations,
            'infos' => $infos
        ]);
    }
}
