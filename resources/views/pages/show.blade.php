<x-app-layout>
    @if($page)
        @if($page['template'] === 'Player')
            <div class="lg:flex lg:space-x-10">
                <div class="lg:w-3/4">
                    <livewire:video-player :player="$player"/>
                </div>
                <div class="lg:w-1/4">
                    <h1 class="text-2xl font-semibold">{{ $page['title'] }}</h1>
                    <div>{!! $page['content'] !!}</div>
                </div>
            </div>
        @elseif($page['template'] === 'PlayerWithChat')
            <div class="lg:flex lg:space-x-4">
                <div class="lg:w-2/3">
                    <livewire:video-player :player="$player"/>
                </div>
                <div class="lg:w-1/3">
                    <livewire:chat :chat="$chat"/>
                </div>
            </div>
        @else
            <h1 class="text-2xl font-semibold">{{ $page['title'] }}</h1>
            <div>{!! $page['content'] !!}</div>
        @endif
    @else
        <h1 class="text-2xl font-semibold">Создайте страницу в админпанели</h1>
    @endif
</x-app-layout>
