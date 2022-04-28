<?php

namespace App\Service\Analytics;

use DateTime;
use App\Entity\Page;
use App\Entity\Visitor;
use App\Repository\VisitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Analytics\RequestAnalytics;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AnalyticsRequestService
{
    // Pour éviter d'enregistrer les URLs de développement et d'admin.
    protected static $closedRoutes = [
        'startWith' => [
            '/analytics',
            '/admin',
            '/_wdt',
            '/_profiler',
            '/_fragment',
            '/paiement/confirmation',
            '/media/cache'
        ],
        'endWith' => [
            '.ico',
            '.jpg',
            '.jpeg'
        ]
    ];

    protected RequestAnalytics $requestAnalytics;
    protected RequestEvent $requestEvent;
    protected EntityManagerInterface $em;
    protected SessionInterface $session;
    protected VisitorRepository $visitorRepo;

    public function __construct(
        RequestEvent $requestEvent,
        EntityManagerInterface $em,
        SessionInterface $session,
        VisitorRepository $visitorRepo
    ) {
        $this->requestEvent = $requestEvent;
        $this->requestAnalytics = new RequestAnalytics($this->requestEvent->getRequest());
        $this->em = $em;
        $this->session = $session;
        $this->visitorRepo = $visitorRepo;
    }

    public function manageVisitor(): void
    {
        if ($this->isPermittedRoute(self::$closedRoutes)) {

            if ($this->isNewVisitor()) $this->startVisit();

            $this->recordPage();
        }
    }

    private function isPermittedRoute(array $closedRoutes): bool
    {
        $currentRoute = new UnicodeString($this->requestAnalytics->getRequestUri());

        $pass = true;
        foreach ($closedRoutes['startWith'] as $closedRoute) {
            if ($currentRoute->startsWith($closedRoute)) {
                $pass = false;
                break;
            }
        }
        foreach ($closedRoutes['endWith'] as $closedRoute) {
            if ($currentRoute->endsWith($closedRoute)) {
                $pass = false;
                break;
            }
        }
        return $pass;
    }

    private function isNewVisitor(): bool
    {
        $newVisitor = $this->session->get('newVisitor', true);
        $ticketTime = $this->session->get('ticketTime', new DateTime());

        $this->session->set('newVisitor', false);
        $this->session->set('ticketTime', $ticketTime);

        if ($this->isConnectedMoreThan30min($this->session->get('ticketTime')) === true) {
            $this->session->set('ticketTime', new DateTime());
            return true;
        }

        return $newVisitor;
    }

    private function isConnectedMoreThan30min(DateTime $createdTicket): bool
    {
        return $createdTicket->diff(new DateTime())->i >= 30 ? true : false;
    }

    private function startVisit(): void
    {
        $visitor = (new Visitor())
            ->setIp($this->requestAnalytics->getClientIp())
            ->setLanguage($this->requestAnalytics->getLanguage())
            ->setUserAgent($this->requestAnalytics->getUserAgent());

        $this->em->persist($visitor);
        $this->em->flush();

        $this->session->set('visitorId', $visitor->getId());
    }

    private function recordPage(): void
    {
        $visitor = $this->visitorRepo->find($this->session->get('visitorId'));

        $page = (new Page())
            ->setDatetime(new \DateTime)
            ->setUri($this->requestAnalytics->getRequestUri())
            ->setVisitor($visitor);

        $this->em->persist($page);
        $this->em->flush();

        $this->session->set('pageId', $page->getId());
        $this->requestEvent->getRequest()->attributes->set('pageId', $page->getId());
    }
}
