<button
    {{ $attributes->merge(['class' =>'tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 tw-text-base tw-font-medium hover:tw-border-gray-800']) }}>
    {{ $slot }}
    <x-icons.caret-down class="tw-ml-2 tw-h-4 tw-w-4 tw-fill-current">
    </x-icons.caret-down>
</button>
