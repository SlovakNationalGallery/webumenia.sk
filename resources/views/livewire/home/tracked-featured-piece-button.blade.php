<a wire:click.prevent="trackClick" href="{{ $featuredPiece->url }}"
    class="{{ $class }} tw-inline-block tw-border tw-border-gray-300 tw-px-4 tw-py-2 tw-text-sm tw-transition tw-duration-300 hover:tw-border-gray-400 hover:tw-bg-white hover:tw-text-gray-800">
    {{ $featuredPiece->is_collection ? 'Prejsť na kolekciu' : 'Prejsť na článok' }}
    <i class="fa icon-arrow-right tw-ml-2"></i>
</a>
