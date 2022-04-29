<?php

namespace App\Service\HttpToHttps;

class HttpToHttpsService
{
    private static string $needle = "http://";
    private static string $freshNeedle = "https://";
    private string $appEnv;

    public function __construct(string $appEnv)
    {
        $this->appEnv = $appEnv;
    }

    public function convert(string $url): string
    {
        if ($this->appEnv === 'prod' && str_starts_with($url, self::$needle)) {
            return str_replace(self::$needle, self::$freshNeedle, $url);
        }
        return $url;
    }
}
