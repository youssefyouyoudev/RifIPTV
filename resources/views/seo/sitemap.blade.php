<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($paths as $path)
    @foreach ($supportedLocales as $locale)
    <url>
        <loc>{{ rtrim(config('app.url'), '/') . $path }}?lang={{ $locale }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $path === route('home', absolute: false) ? '1.0' : '0.7' }}</priority>
    </url>
    @endforeach
@endforeach
</urlset>
