<button {{ $attributes->merge(['class' => 'tw-pr-2 tw-flex tw-items-center tw-py-0.5']) }}>
    <x-icons.caret-left class="tw-h-6 tw-w-6 tw-fill-current"></x-icons.caret-left>
    {{ $slot }}
</button>
