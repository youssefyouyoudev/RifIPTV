<?php

namespace App\Support;

class SeoUrl
{
    public static function withLocale(string $url, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $defaultLocale = config('app.locale', 'en');

        $parts = parse_url($url);
        $scheme = $parts['scheme'] ?? parse_url(config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?? 'https';
        $host = $parts['host'] ?? parse_url(config('app.url', 'http://localhost'), PHP_URL_HOST) ?? 'localhost';
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';
        $path = $parts['path'] ?? '/';
        $query = [];

        if ($locale !== $defaultLocale) {
            $query['lang'] = $locale;
        }

        $queryString = $query ? '?'.http_build_query($query) : '';

        return "{$scheme}://{$host}{$port}{$path}{$queryString}";
    }

    public static function localeMap(string $url, array $locales): array
    {
        $map = [];

        foreach ($locales as $locale) {
            $map[$locale] = self::withLocale($url, $locale);
        }

        return $map;
    }

    public static function xDefault(string $url): string
    {
        return self::withLocale($url, config('app.locale', 'en'));
    }
}
