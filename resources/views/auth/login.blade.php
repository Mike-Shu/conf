<x-guest-layout>
    <div class="w-full lg:max-w-6xl mx-auto">
        <div class="flex flex-wrap items-center justify-center lg:flex-row flex-col-reverse">
            <div class="w-full lg:w-1/2">
                <div class="card p-8 mx-4 lg:mx-0">
                    <h1 class="text-2xl font-bold mb-2">{{ __('Login') }}</h1>
                    <x-jet-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div>
                            <x-jet-label for="email" value="{{ __('Email') }}" />
                            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-jet-label for="password" value="{{ __('Password') }}" />
                            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <div>
                                <label for="remember_me" class="flex items-center">
                                    <x-jet-checkbox id="remember_me" name="remember" />
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <x-jet-button class="mt-4 w-full text-center inline-flex justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white hover:text-white uppercase font-bold rounded">
                            {{ __('Login') }}
                        </x-jet-button>

                        <div class="flex items-center justify-center mt-4">
                            <span class="text-sm text-theme-text mr-4">{{ __("Don't have account?") }}</span>
                            <a href="{{ route('register') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Register') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="w-full lg:w-1/2">
                <div class="p-8"><livewire:logo/></div>
            </div>
        </div>
    </div>
</x-guest-layout>
