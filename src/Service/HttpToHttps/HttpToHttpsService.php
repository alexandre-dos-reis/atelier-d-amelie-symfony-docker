<?php

namespace App\Service\HttpToHttps;

class HttpToHttpsService
{
    private static string $needle = "http://";
    private static string $freshNeedle = "https://";

    public function convert(string $url): string
    {
        if (str_starts_with($url, self::$needle)) {
            return str_replace(self::$needle, self::$freshNeedle, $url);
        }
        return $url;
    }
}
