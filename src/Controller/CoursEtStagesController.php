<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursEtStagesController extends AbstractController
{
    /**
     * @Route("/cours-et-stages", name="cours_et_stages")
     */
    public function index(): Response
    {
        return $this->render('cours_et_stages/index.html.twig', []);
    }
}
