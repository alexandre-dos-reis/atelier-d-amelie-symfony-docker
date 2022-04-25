<?php

namespace App\Service\Analytics;

use Symfony\Component\HttpFoundation\Response;

class ResponseAnalytics
{
    /**
    * @var Response 
    */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }
}
