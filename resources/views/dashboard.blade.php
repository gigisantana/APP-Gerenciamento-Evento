<x-app-layout>
    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8 bg-lime-600 px-10 py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg align-top justify-between">
                <div class="p-6 text-green-500">
                    {{ __("You're logged in!") }}
                </div>
                <div class=""> 
                    <h1 class="text-2xl font-bold mb-4"></h1>
                    <img src="{{ asset('images/mapa-campus.svg') }}" alt="Mapa do Campus" class="mapa-campus">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
