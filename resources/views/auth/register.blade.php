<x-guest-layout>
    <div class="w-full lg:max-w-6xl mx-auto">
        <div class="flex flex-wrap items-center justify-center lg:flex-row flex-col-reverse">
            <div class="w-full lg:w-1/2">
                <div class="card p-8 mx-4 lg:mx-0">
                    <h1 class="text-2xl font-bold mb-2">{{ __('Register') }}</h1>
                    <x-jet-validation-errors class="mb-4" />
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div>
                            <x-jet-label for="name" value="{{ __('Name') }}" />
                            <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        </div>

                        <div class="mt-4">
                            <x-jet-label for="email" value="{{ __('Email') }}" />
                            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mt-4">
                                <x-jet-label for="terms">
                                    <div class="flex items-center">
                                        <x-jet-checkbox name="terms" id="terms" required />

                                        <div class="ml-2">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-jet-label>
                            </div>
                        @endif


                        <x-jet-button class="mt-4 w-full text-center inline-flex justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white hover:text-white uppercase font-bold rounded">
                            {{ __('Register') }}
                        </x-jet-button>

                        <div class="flex items-center justify-center mt-4">
                        <span class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                            {{ __('Already registered?') }}
                        </span>
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                                {{ __('Login') }}
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
