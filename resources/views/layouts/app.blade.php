<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Gestor de Eventos - IFPR</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://ifpr.edu.br/wp-content/themes/tema-multisite/assets/images/favicon.gif" rel="shortcut icon" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    </head>
    <body class="font-sans antialiased bg-lime-500">
        <div class="min-h-screen">
            @include('layouts.navigation')            
            <!-- Page Heading -->
            @if (isset($header))
                <header class="">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            
            <!-- Page Content -->
            <main class="mx-auto my-auto sm:px-6 lg:px-8 px-10 py-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @yield('content')
                </div>   
            </main>
        </div>
    </body>
</html>
