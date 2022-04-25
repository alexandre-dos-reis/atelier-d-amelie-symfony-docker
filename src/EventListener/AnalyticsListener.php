<?php

namespace App\EventListener;

use App\Repository\PageRepository;
use App\Repository\VisitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Analytics\AnalyticsRequestService;
use App\Service\Analytics\AnalyticsResponseService;
use App\Service\Analytics\RequestAnalytics;
use App\Service\Analytics\ResponseAnalytics;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AnalyticsListener
{
    private $em;
    private $session;
    private $visitorRepo;
    private $pageRepo;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, VisitorRepository $visitorRepo, PageRepository $pageRepo)
    {
        $this->em = $em;
        $this->session = $session;
        $this->visitorRepo = $visitorRepo;
        $this->pageRepo = $pageRepo;
    }

    public function onEachRequest(RequestEvent $requestEvent)
    {   
        return (new AnalyticsRequestService($requestEvent, $this->em, $this->session, $this->visitorRepo))
            ->manageVisitor();
    }

    public function onEachResponse(ResponseEvent $responseEvent)
    {
        return (new AnalyticsResponseService($responseEvent, $this->em, $this->session, $this->pageRepo))
            ->interceptHttpStatusCode();
    }
}
