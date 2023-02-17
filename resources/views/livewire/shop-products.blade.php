@if($entities)
    <div class="grid grid-cols-4 gap-4">
        @foreach($entities as $entity)
            <div class="card">
                <div class="card-media h-80">
                    <div class="card-media-overlay"></div>
                    <img src="{{ $entity->thumb }}" alt="">
                </div>
                <div class="card-body">
                    <div class="-top-3 absolute bg-blue-100 font-medium px-2 py-1 right-2 rounded-full text text-blue-500 text-sm">
                        {{ $entity->price }}
                    </div>
                    <div class="text-xl font-bold mt-1 truncate">{{ $entity->title }}</div>
                    <div>{!! $entity->description !!}</div>
                    <div class="w-full text-center text-white bg-blue-600 p-2">
                        <a wire:click.prevent="addToCart({{ $entity->id }})"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                           href="#" class="btn btn-sm btn-primary">{{ __('Add to cart') }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <h1 class="text-2xl font-semibold">Не найдено товаров</h1>
@endif
