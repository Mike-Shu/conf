<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WeConf Events') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        @stack('page-styles')
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8"><livewire:logo/></div>
                @if (Route::has('login'))
                    <div class="flex justify-center">
                        @guest
                            <a href="{{ route('login') }}" class="inline-block px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white hover:text-white uppercase font-bold rounded">{{ __('Login') }}</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 inline-block px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white hover:text-white uppercase font-bold rounded">{{ __('Register') }}</a>
                            @endif
                        @endguest
                    </div>
                @endif
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        @livewire('notifications')

        @stack('page-scripts')
    </body>
</html>
