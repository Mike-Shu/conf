<div x-data="{ userCorrectAnswersCount: @entangle('userCorrectAnswersCount').defer, loaded: false }">
    <h1 class="text-2xl font-semibold mb-6">{{ $quiz->title }}</h1>

    <div x-show="loaded"
         x-init="$nextTick(() => {$el.classList.remove('hidden'); loaded = true;})"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="hidden">
        @if(!$isQuizPassed)
            <div x-show="userCorrectAnswersCount == null"
                 x-data="{currentQuestionNumber: @entangle('currentQuestionNumber')}">
                @foreach ($quiz->questions as $_question)
                    <div x-show="currentQuestionNumber == {{$loop->iteration}}"
                         x-data="{ answer_selected: @entangle('selected').defer }">
                        {{-- Question content --}}
                        <div class="mb-6 text-black quiz-question-content">{!! $_question->content !!}</div>

                        {{-- Help text --}}
                        <div class="mb-4 text-lg text-gray-500 font-medium">
                            @if($_question->multiple)
                                {{ __('Choose as many answers as you like') }}
                            @else
                                {{ __('Choose only one answer') }}
                            @endif
                        </div>

                        {{-- List of answers --}}
                        <div class="flex-col space-y-4 mb-6">
                            @foreach ($_question->answers as $_index => $_answer)
                                @if($_question->multiple)
                                    <label
                                        class="flex items-center p-4 border border-gray-200 bg-white hover:border-gray-400 cursor-pointer">
                                        <input @click="answer_selected = true"
                                               wire:model="multipleAnswer"
                                               type="checkbox"
                                               value="{{ $_answer->id }}"
                                               class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 focus:ring-2">
                                        <div class="ml-4 font-medium text-gray-900">{!! $_answer->content !!}</div>
                                    </label>
                                @else
                                    <label
                                        class="flex items-center p-4 border border-gray-200 bg-white hover:border-gray-400 cursor-pointer">
                                        <input @click="answer_selected = true"
                                               wire:model="singleAnswer"
                                               type="radio"
                                               value="{{ $_answer->id }}"
                                               name="single-answer"
                                               class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 focus:ring-2">
                                        <div class="ml-4 font-medium text-gray-900">{!! $_answer->content !!}</div>
                                    </label>
                                @endif
                            @endforeach
                        </div>

                        {{-- Control buttons --}}
                        <div class="flex items-center" x-data="{ loading: @entangle('loading').defer }">
                            <button
                                x-show="answer_selected"
                                x-on:click="loading = true"
                                x-bind:disabled="loading"
                                wire:click="applyAnswer({{ $_question->id }})"
                                class="flex bg-green-500 hover:bg-green-700 text-white text-xl py-3 px-6 border border-green-700 rounded">
                                <x-filament-support::loading-indicator
                                    x-show="loading"
                                    class="inline-block w-7 h-7 text-white mr-2"
                                />
                                <div>{{ __('Ok') }}</div>
                            </button>
                            <button
                                x-show="!answer_selected"
                                x-on:click="loading = true"
                                x-bind:disabled="loading"
                                wire:click="skipQuestion({{ $_question->id }})"
                                class="flex bg-green-500 hover:bg-green-700 text-white text-xl py-3 px-6 border border-green-700 rounded">
                                <x-filament-support::loading-indicator
                                    x-show="loading"
                                    class="inline-block w-7 h-7 text-white mr-2"
                                />
                                <div>{{ __('Skip') }}</div>
                            </button>
                            <div class="ml-4 text-lg text-gray-500 font-medium">
                                {{ __('Choose the correct answer or skip it') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div x-show="userCorrectAnswersCount != null">
                <div class="mb-6 text-xl text-black">{{ __('You passed this quiz') }}!</div>
                <div class="text-lg text-gray-500">
                    {{ __('Correct answers') }}:
                    {{ $this->userCorrectAnswersCount }} {{ __('of') }} {{ $quiz->questions->count() }}
                </div>
                @if($quiz->final_text)
                    <div class="mt-6 text-base text-black">
                        {!! $quiz->final_text !!}
                    </div>
                @endif
            </div>
        @else
            <div class="mb-6 text-xl text-black">{{ __('You passed this quiz') }}</div>
            <div class="text-lg text-gray-500">
                {{ __('Correct answers') }}:
                {{ $this->userCorrectAnswersCount }} {{ __('of') }} {{ $quiz->questions->count() }}
            </div>
        @endif
    </div>
</div>
