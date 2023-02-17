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
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div id="wrapper" class="min-h-screen">

            <!-- Header -->
            <header>
                <div class="header_wrap">
                    <div class="header_inner mcontainer">
                        <div class="left_side">

                        <span class="slide_menu" uk-toggle="target: #wrapper ; cls: is-collapse is-active">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M3 4h18v2H3V4zm0 7h12v2H3v-2zm0 7h18v2H3v-2z" fill="currentColor"></path></svg>
                        </span>
                            <div id="logo">
                                <a href="{{ route('pages.show-main') }}">
                                    <livewire:logo />
                                </a>
                            </div>
                        </div>

                        <div class="right_side">
                            <div class="header_widgets">
                                <div class="flex w-full items-center justify-between">
                                    <div class="lg:mr-4">
                                        <livewire:shop-cart-widget/>
                                    </div>
                                    <a href="{{ route('wallet.show') }}" class="flex items-center lg:mr-4 cursor-pointer">
                                        <span class="hidden lg:block text-lg mr-2">{{ Auth::user()->balance }}</span>
                                        <span>@svg('ri-bit-coin-line', 'w-8 h-8')</span>
                                    </a>
                                    <a class="flex items-center cursor-pointer">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <div class="shrink-0 mr-3">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                            </div>
                                        @endif

                                        <span class="hidden lg:block mr-2">{{ Auth::user()->name }}</span>
                                        <span class>@svg('ri-user-line', 'w-8 h-8')</span>
                                    </a>
                                    <div uk-drop="mode: click;offset:5" class="header_dropdown profile_dropdown">

                                        <a href="{{ route('profile.show') }}" class="user">
                                            <div class="user_avatar">
                                                @svg('ri-user-line', 'w-8 h-8')
                                            </div>
                                            <div class="user_name">
                                                <div>{{ Auth::user()->name }}</div>
                                                <div>{{ Auth::user()->email }}</div>
                                                <hr />
                                                <div>{{ __('Balance') }}: {{ Auth::user()->balance }}</div>
                                            </div>
                                        </a>

                                        <hr>

                                        <!-- Account Management -->
                                        <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                                            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                                            {{ __('Profile') }}
                                        </x-jet-responsive-nav-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}" x-data>
                                            @csrf

                                            <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                                                       @click.prevent="$root.submit();">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                {{ __('Logout') }}
                                            </x-jet-responsive-nav-link>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Sidebar -->
            <div class="sidebar">

                <div class="sidebar_inner" data-simplebar>
                    <livewire:show-main-menu />
                </div>

                <!-- sidebar overly for mobile -->
                <div class="side_overly" uk-toggle="target: #wrapper ; cls: is-collapse is-active"></div>

            </div>

            <!-- Page Content -->
            <div class="main_content">
                <div class="mcontainer max-w-screen-2xl">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        @livewire('notifications')

        @stack('page-scripts')
    </body>
</html>
