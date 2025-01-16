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
        <script src="https://unpkg.co/gsap@3/dist/gsap.min.js" defer></script>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">


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
            <main class="mx-auto my-auto sm:px-6 lg:px-8 px-10 py-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="align-top flex flex-wrap divide-x-2 divide-dashed divide-lime-500">
                        <div class="p-6 basis-2/5">                        
                            @yield('content')
                        </div>
                        <div class="p-4 basis-3/5 px-8"> 
                            <h1 class="text-2xl font-bold text-lime-700 ">Mapa do Campus Paranaguá</h1>
                            <div class="flex justify-center items-center">
                                {!! file_get_contents(public_path('images/mapa-IFPR-2.svg')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                @if (session('message'))
                <div class="bg-green-100 text-green-700 p-4 rounded-md shadow-md mb-6">
                    {{ session('message') }}
                </div>
                @endif
            -->
            </main>
        </div>
    </body>
</html>
