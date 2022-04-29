<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HttpToHttpsExtension extends AbstractExtension
{
    private static string $needle = "http://";
    private static string $freshNeedle = "https://";

    public function getFunctions()
    {
        return [
            new TwigFunction('httpToHttps', [$this, 'httpToHttps']),
        ];
    }

    public function httpToHttps(string $url): string
    {
        if (str_starts_with($url, self::$needle)) {
            return str_replace(self::$needle, self::$freshNeedle, $url);
        }
        return $url;
    }
}
