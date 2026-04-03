<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="0;url={{ route('home') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'RIF Streaming') }}</title>
</head>
<body>
    <p><a href="{{ route('home') }}">Continue to RIF Streaming</a></p>
</body>
</html>

