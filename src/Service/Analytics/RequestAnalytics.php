<?php

namespace App\Service\Analytics;

use Symfony\Component\HttpFoundation\Request;

class RequestAnalytics
{
    /**
     * @var Request 
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getClientIp(): string
    {
        return $this->request->getClientIp();
    }

    public function getRequestUri(): string
    {
        return $this->request->getRequestUri();
    }

    public function getLanguage(): string
    {
        return $this->request->getPreferredLanguage();
    }

    public function getUserAgent(): string
    {
        return $this->request->server->getHeaders()['USER_AGENT'];
    }

    public function getReferer(): string
    {
        $referer = $this->request->server->get('HTTP_REFERER');
        return $referer === null ? '' : $referer;
    }
}