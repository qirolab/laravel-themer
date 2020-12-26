<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>::theme::</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        {{-- <!-- Styles -->
        <link rel="stylesheet" href="{{ theme_asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ theme_asset('js/app.js') }}" defer></script> --}}
    </head>
    <body>
        <h1>Theme: ::theme::</h1>
    </body>
</html>
