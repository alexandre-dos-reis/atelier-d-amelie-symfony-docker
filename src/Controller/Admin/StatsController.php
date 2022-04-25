<?php

namespace App\Controller\Admin;

use App\Repository\PageRepository;
use App\Repository\VisitorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatsController extends AbstractController
{
    /**
     * @Route("/admin/stats", name="stats")
     */
    public function index(PageRepository $pageRepo, VisitorRepository $visitorRepo): Response
    {
        return $this->render('admin/stat.html.twig', [
            'pages10LessViewed' => $pageRepo->findFrequentUriRequested('ASC', 10),
            'pages10MostViewed' => $pageRepo->findFrequentUriRequested('DESC', 10),
            'countAllPages' => $pageRepo->countAllPages(),
            'countAllPagesSince12Months' => $pageRepo->countAllPagesSince12Months(),
            'countAllPagesSince30Days' => $pageRepo->countAllPagesSince30Days(),
            'countAllPagesSince24Hours' => $pageRepo->countAllPagesSince24Hours(),
            'averagePagesPerSession' => $pageRepo->findAveragePagesPerSession(),
            'averageTimePerSession' => $pageRepo->findAverageTimePerSession(),
            'httpStatusCodePerPercentage' => $pageRepo->findHttpStatusCodePerPercentage(),
            'uniqueVisitors' => $visitorRepo->findUniqueVisitors()
        ]);
    }
}
