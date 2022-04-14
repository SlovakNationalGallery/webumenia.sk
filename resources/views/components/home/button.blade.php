<a
    {{ $attributes->merge(['class' =>'tw-group tw-inline-block tw-border tw-border-gray-300 tw-px-4 tw-py-2 tw-text-sm tw-transition tw-duration-300 hover:tw-border-gray-400 hover:tw-bg-white hover:tw-text-gray-800']) }}>
    {{ $slot }}
    <i
        class="fa icon-arrow-right tw-ml-1.5 tw--mb-2 tw-align-[-0.05rem] tw-transition-transform group-hover:tw-translate-x-0.5"></i>
</a>
