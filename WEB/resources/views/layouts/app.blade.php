<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CESIZen') | Ministère de la Santé</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --fr-blue: #000091;
            --cz-navy: #000074;
            --cz-mustard: #715200;
            --cz-mustard-bg: #F8B803;
            --cz-jade: #00635B;
        }
    </style>
</head>
@include('layouts.header')
<body class="bg-[#F6F6F6] text-[#161616] min-h-screen font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar Simplifiée pour les sous-pages -->
        @include('layouts.sidebar')

        <main class="flex-1">
            <!-- <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between">
                <h1 class="text-xl font-bold">@yield('header_title')</h1>
                @auth
                    <span class="text-sm font-bold text-slate-600">{{ Auth::user()->name }}</span>
                @endauth
            </header> -->
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
