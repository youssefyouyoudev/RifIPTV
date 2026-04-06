<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $metaDescription }}">
<meta name="robots" content="{{ $metaRobots }}">
<meta name="author" content="{{ $brandName }}">
<meta name="application-name" content="{{ $brandName }}">
<meta name="color-scheme" content="light dark">
<meta name="theme-color" media="(prefers-color-scheme: light)" content="#F8FAFC">
<meta name="theme-color" media="(prefers-color-scheme: dark)" content="#020617">
<link rel="canonical" href="{{ $canonicalUrl }}">
@foreach ($localeUrls as $locale => $localeUrl)
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ $localeUrl }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ $localizedBaseUrl }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="{{ $brandName }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:locale" content="{{ $ogLocale }}">
<meta property="og:image" content="{{ $defaultOgImage }}">
<meta property="og:image:alt" content="{{ $brandName }} device setup and technical support preview">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $defaultOgImage }}">
<meta name="twitter:image:alt" content="{{ $brandName }} device setup and technical support preview">
<link rel="icon" href="{{ $brandLogo }}" type="image/png">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<link rel="alternate" type="text/plain" href="{{ url('/llms.txt') }}" title="LLMs.txt">
@foreach ($socialProfiles as $socialProfile)
    <link rel="me" href="{{ $socialProfile }}">
@endforeach
<title>{{ $metaTitle }}</title>
