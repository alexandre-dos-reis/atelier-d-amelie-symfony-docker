<?php

namespace App\Twig;

use App\Service\HttpToHttps\HttpToHttpsService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HttpToHttpsExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('httpToHttps', [$this, 'httpToHttps']),
        ];
    }

    public function httpToHttps(string $url): string
    {
        return (new HttpToHttpsService())->convert($url);
    }
}
