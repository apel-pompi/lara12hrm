<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="application-name" content="Student Management System (CRM)">
    <meta name="author" content="{{ config('developer.author') }}">
    <meta name="developer" content="Full Stack Web Developer (PHP, Laravel, Vue.js)">
    <meta name="generator" content="Laravel + Vue.js">
    <meta name="contact" content="{{ config('developer.email') }}">



    <meta property="og:title" content="Student Management System For Foreign Study">
    <meta property="og:description" content="CRM Project with laravel vue">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/preview.png">
    <meta property="og:site_name" content="Developed by Md. Ashrafur Rahman">
    <meta property="og:author" content="Md. Ashrafur Rahman">
    <meta name="robots" content="index, follow">
    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script>
        (function() {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    {{-- Inline style to set the HTML background color based on our theme in app.css --}}
    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    <title inertia>{{ config('app.name', 'Student Management System For Foreign Study') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="icon" type="image/png" href="/favicon.png">

    @routes
    @vite(['resources/js/app.ts'])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>