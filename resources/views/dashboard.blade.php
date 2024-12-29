<!-- Página de dashboard de eventos, com mapa e uma lista simples de eventos próximos -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Gestor de Eventos - IFPR</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    </head>
    <body>
        <x-app-layout>
            <div class="border-4 border-red-700">
                <div class="mx-auto sm:px-6 lg:px-8 bg-lime-500 px-10 py-8 border-4 border-blue-500">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg align-top justify-around flex flex-wrap divide-x border-4 border-red-700">
                        <div class="p-6 basis-2/5 border-4 border-red-700">
                            <h1 class="text-2xl font-bold text-lime-700 mb-4">Eventos do IFPR</h1>
                            <!-- COLOCAR AQUI A LÓGICA PRA APARECER OS EVENTOS PRÓXIMOS!!! -->
                            <p class="text-gray-500">Aqui você encontra os eventos próximos Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore dicta voluptate ducimus non necessitatibus, neque expedita fugiat quos. Quam fugiat quisquam eaque tempora vero repellat, officia ducimus minima at dolores? </p>
                        </div>
                        <div class="p-6 basis-3/5 border-4 border-blue-700"> 
                            <h1 class="text-2xl font-bold text-lime-700 mb-4">Mapa do Campus Paranaguá</h1>
                            <img src="{{ asset('images/mapa-campus.svg') }}" alt="Mapa do Campus" class="mapa-campus">
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>
