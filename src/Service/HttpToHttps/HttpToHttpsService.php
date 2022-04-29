<?php

namespace App\Service\HttpToHttps;

use Symfony\Component\HttpFoundation\RequestStack;

class HttpToHttpsService
{
    private static string $needle = "http://";
    private static string $freshNeedle = "https://";
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function convert(string $url): string
    {
        dd($this->requestStack->getCurrentRequest());

        if (str_starts_with($url, self::$needle)) {
            return str_replace(self::$needle, self::$freshNeedle, $url);
        }
        return $url;
    }
}
