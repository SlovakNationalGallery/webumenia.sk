<button
    {{ $attributes->merge(['class' =>'tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 tw-text-base tw-font-medium hover:tw-border-gray-800']) }}>
    {{ $slot }}
    <svg class="tw-ml-2 tw-h-4 tw-w-4 tw-fill-current" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 256 256">
        <path
            d="M216.49,104.49l-80,80a12,12,0,0,1-17,0l-80-80a12,12,0,0,1,17-17L128,159l71.51-71.52a12,12,0,0,1,17,17Z">
        </path>
    </svg>
</button>
