<div wire:poll.visible class="Chat card flex flex-col mb-4">
    <div class="Chat__header h-12 flex items-center px-4"><span class="font-bold text-xl">{{ __('Chat') }}</span></div>
    <div class="Chat__body bg-gray-100 pl-4 py-4 pr-2">
        <div class="h-full min-h-full overflow-y-auto">
            @foreach($messages as $message)
                <div class="mb-2">
                    <div class="inline-block bg-white p-3 rounded-md border max-w-[360px] break-words text-sm">
                        <span class="font-bold">{{ $message->user ? $message->user->name : "Гость" }}</span>
                        <p>{{ $message->text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="Chat__footer h-12">
        <form wire:submit.prevent="sendMessage" class="h-full">
            <div class="flex items-center md:inline-flex h-full w-full px-2">
                <div class="md:w-10/12 w-full max-w-sm mx-auto space-y-5">
                    <input wire:model.defer="messageText" type="text"
                           placeholder="{{ __('Message text') }}"
                           class="px-2 w-full py-2 border-0 outline-0"/>
                </div>

                <div class="md:w-2/12 space-y-5">
                    <button type="submit" class="px-4">
                        @svg('ri-send-plane-2-fill', 'text-blue-500 h-8 w-8')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
