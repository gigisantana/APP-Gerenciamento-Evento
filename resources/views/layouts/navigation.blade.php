<nav x-data="{ open: false }" class="border-b bg-lime-50">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="flex justify-between h-12">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-8 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('sobre')" :active="request()->routeIs('sobre')">
                        {{ __('Sobre') }}
                    </x-nav-link>
                </div>
            </div>

            @if (Auth::check())
            
            <!-- Settings Dropdown for Authenticated Users -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (Auth::user()->is_servidor_ifpr)
                <div>
                    <a href="{{ route('evento.create') }}" class="inline-flex items-center px-2 py-1.5 border border-lime-600 text-sm leading-4 font-medium rounded-md text-lime-50 bg-lime-600 hover:bg-lime-100 hover:text-lime-700 focus:outline-none transition ease-in-out duration-150 me-4">
                        {{ __('+ Criar Evento') }}
                    </a>
                </div>
                @endif
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-2 py-2 border border-lime-200 text-sm leading-4 font-medium rounded-md text-lime-700 bg-white hover:text-lime-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->nome }} {{ Auth::user()->sobrenome ?? '' }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Editar o perfil') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.inscricoes')">
                                {{ __('Inscrições') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.gerenciamento')">
                                {{ __('Gerenciar Eventos') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <!-- Login/Register Buttons for Guests -->
                <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                    <a href="{{ route('login') }}" class="text-sm text-green-700 underline hover:text-green-900">
                        {{ __('Entrar') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-sm text-green-700 underline hover:text-green-900">
                        {{ __('Registrar') }}
                    </a>
                </div>
            @endif

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-green-400 hover:text-green-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-green-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @if (Auth::check())
            <div class="pt-4 pb-1 border-t border-green-200">
                <div class="px-4">
                    <div class="font-medium text-base text-green-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-green-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Editar Perfil') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.inscricoes')">
                        {{ __('Inscrições') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-green-200">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Entrar') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Registrar') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endif
    </div>
</nav>
