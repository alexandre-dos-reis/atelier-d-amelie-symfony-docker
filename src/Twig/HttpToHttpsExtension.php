<?php

namespace App\Twig;

use App\Service\HttpToHttps\HttpToHttpsService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HttpToHttpsExtension extends AbstractExtension
{
    private HttpToHttpsService $httpToHttpsService;

    public function __construct(HttpToHttpsService $httpToHttpsService)
    {
        $this->httpToHttpsService = $httpToHttpsService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('httpToHttps', [$this, 'httpToHttps']),
        ];
    }

    public function httpToHttps(string $url): string
    {
        return $this->httpToHttpsService->convert($url);
    }
}
