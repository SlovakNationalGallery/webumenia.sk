<button
    {{ $attributes->merge(['class' =>'tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-xs md:tw-text-sm tw-font-normal hover:tw-border-gray-800']) }}>
    <x-icons.arrow-counter-clockwise class="tw-mr-1.5 tw-h-4 tw-w-4 tw-fill-current">
    </x-icons.arrow-counter-clockwise>
    {{ $slot }}
</button>
