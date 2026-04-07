<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($items as $item)
    <url>
        <loc>{{ $item['loc'] }}</loc>
@foreach ($item['alternates'] as $locale => $alternateUrl)
        <xhtml:link rel="alternate" hreflang="{{ $locale }}" href="{{ $alternateUrl }}" />
@endforeach
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ $item['loc'] }}" />
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $item['priority'] }}</priority>
    </url>
@endforeach
</urlset>
