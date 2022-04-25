<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrackerController extends AbstractController
{
    /**
     * @Route("/suivi-de-commande/{uuid}", name="tracker_index")
     */
    public function index(PurchaseRepository $purchaseRepository, $uuid): Response
    {
        $purchase = $purchaseRepository->findOneBy([
            'uuid' => $uuid
        ]);

        if (!$purchase) throw $this->createNotFoundException("Cette commande n'existe pas !");

        return $this->render('tracker/index.html.twig', [
            'p' => $purchase
        ]);
    }
}
