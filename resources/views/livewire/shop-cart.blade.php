<div class="flex flex-wrap">
    <div class="w-full lg:w-2/3">
        <ul>
            @foreach($entities as $entity)
                <li class="card p-4">
                    <div class="cart_avatar">
                        <img src="{{ $entity->options->picture }}" alt="">
                    </div>
                    <div class="cart_text">
                        <div class=" font-semibold leading-4 mb-1.5 text-base line-clamp-1">{{ $entity->name }}</div>
                    </div>
                    <div class="cart_price">
                        <span>{{ $entity->price }}</span>
                        <span>{{ $entity->qty }}</span>
                        <span>{{ $entity->rowId }}</span>
                        <a wire:click.prevent="removeFromCart('{{ $entity->rowId }}')"
                           class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                           href="#" class="btn btn-sm btn-danger">{{ __('Remove') }}</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="w-full lg:w-1/3">
        <div class="card">
            Чекаут
        </div>
    </div>
</div>


