<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex justify-between my-4 items-center">
            <div>
            <label for="remember_me">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-lime-600 shadow-sm focus:ring-lime-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Lembrar-me') }}</span>
            </label>
            </div>
            <div>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Esqueceu sua senha?') }}
                </a>
            @endif
            </div>
        </div>
        <div class="ms-3 flex justify-center my-2">
            <x-primary-button>
                {{ __('Entrar') }}
            </x-primary-button>
        </div>
            
        <div class="place-items-center text-gray-600 my-4">
                <p class="text-sm"> É sua primeira vez por aqui? 

                    <a class="underline text-sm  hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500" href="{{ route('register') }}">
                        {{ __('Crie uma conta!') }}
                    </a>
                </p>
        </div>
                
    </form>
</x-guest-layout>
