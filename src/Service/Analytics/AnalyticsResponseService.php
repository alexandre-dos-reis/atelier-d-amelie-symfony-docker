<?php

namespace App\Service\Analytics;

use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Analytics\ResponseAnalytics;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AnalyticsResponseService
{
    protected $responseEvent;
    protected $responseAnalytics;
    protected $em;
    protected $session;
    protected $pageRepo;

    public function __construct(
        ResponseEvent $responseEvent,
        EntityManagerInterface $em,
        SessionInterface $session,
        PageRepository $pageRepo
    ) {
        $this->responseEvent = $responseEvent;
        $this->responseAnalytics = new ResponseAnalytics($responseEvent->getResponse());
        $this->em = $em;
        $this->session = $session;
        $this->pageRepo = $pageRepo;
    }

    public function interceptHttpStatusCode(): void
    {
        $pageId = $this->responseEvent->getRequest()->attributes->get('pageId');
        
        if (isset($pageId)) {
            $page = $this->pageRepo->find($pageId);
            $page->setHttpStatusCode($this->responseAnalytics->getStatusCode());

            $this->em->persist($page);
            $this->em->flush();
        }
    }
}
