<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AnalyticsController extends AbstractController
{
    /**
     * @Route("/analytics/timeSpentOnPage", name="analytics_timeSpentOnPage", methods={"POST"})
     */
    public function timeSpentOnPage(Request $request, PageRepository $pageRepo, EntityManagerInterface $em): Response
    {
        $params = json_decode($request->getContent(), true);

        $pageId = $params['pageId'];
        $timeSpent = $params['timeSpent'];

        $page = $pageRepo->find($pageId);
        $page->setTimeSpent($timeSpent);

        $em->persist($page);
        $em->flush();

        return $this->json($page->getId());
    }

    /**
     * @Route("/analytics/endVisit", name="analytics_endVisit", methods={"GET"})
     */
    public function endVisit(SessionInterface $session): Response
    {
        $session->set('newVisitor', true);
        return $this->json('The visit has ended. See you next time !');
    }
}
