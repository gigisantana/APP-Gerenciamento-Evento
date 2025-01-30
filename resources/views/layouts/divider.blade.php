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

        <style>
            #tooltip {
                position: absolute;
                display: none;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 5px;
                border-radius: 10px;
                pointer-events: none;
            }

            .scrollable-content::-webkit-scrollbar {
                width: 8px;
            }

            .scrollable-content::-webkit-scrollbar-track {
                background: #ecfccb;
                border-radius: 10px;
            }

            .scrollable-content::-webkit-scrollbar-thumb {
                background-color: #bef264;
                border-radius: 10px;
                border: 2px solid #f1f1f1;
            }

            .scrollable-content::-webkit-scrollbar-thumb:hover {
                background-color: #84cc16;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-lime-400">
        <div class="">
            @include('layouts.navigation')
                @if (session('message'))
                    <div class="bg-green-100 text-green-700 p-4 rounded-md shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                @endif
            <!-- Page Heading -->
            @if (isset($header))
                <header class="">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="mx-auto my-auto sm:px-6 lg:px-8 px-10 py-4 overflow-hidden">
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="align-top flex flex-wrap">
                        <!-- Conteúdo com rolagem interna, sem ultrapassar o limite da tela -->
                        <div class="p-6 basis-2/5 overflow-y-auto max-h-[calc(100vh-85px)] scrollable-content">                        
                            @yield('content')
                        </div>
            
                        <!-- Mapa estático -->
                        <div class="p-3 basis-3/5 px-8"> 
                            <h1 class="text-2xl font-bold text-lime-700 ">Mapa do Campus Paranaguá</h1>
                            <p>Passe o mouse na imagem para mais detalhes!</p>
                            <div class="flex justify-center items-center">
                                {!! file_get_contents(public_path('images/mapa-IFPR-2.svg')) !!}
                            </div>
                            <div id="tooltip"></div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const tooltip = document.getElementById('tooltip');
                                    const elementos = document.querySelectorAll('[data-tooltip]');
                            
                                    elementos.forEach(elemento => {
                                        elemento.addEventListener('mouseenter', function (event) {
                                            tooltip.innerHTML = elemento.getAttribute('data-tooltip');
                                            tooltip.style.display = 'block';
                                            tooltip.style.left = event.pageX + 'px';
                                            tooltip.style.top = (event.pageY + 10) + 'px';
                                        });
                            
                                        elemento.addEventListener('mousemove', function (event) {
                                            tooltip.style.left = event.pageX + 'px';
                                            tooltip.style.top = (event.pageY + 10) + 'px';
                                        });
                            
                                        elemento.addEventListener('mouseleave', function () {
                                            tooltip.style.display = 'none';
                                        });
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
