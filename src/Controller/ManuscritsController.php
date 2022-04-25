<?php

namespace App\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManuscritsController extends AbstractController
{
    /**
     * @Route("/manuscrits", name="manuscrits")
     */
    public function index(): Response
    {
        $manuscrits1 = (new Finder)->files()->in('static/manuscrit_1')->sortByName();
        $manuscrits2 = (new Finder)->files()->in('static/manuscrit_2')->sortByName();

        return $this->render('manuscrits/index.html.twig', [
            'manuscrits1' => $manuscrits1,
            'manuscrits2' => $manuscrits2
        ]);
    }
}
