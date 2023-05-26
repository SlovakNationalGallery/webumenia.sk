@props([
    'sm' => false,
])

@php
$class = ['tw-flex tw-items-center tw-border tw-leading-none tw-border-gray-300 tw-bg-white tw-font-normal hover:tw-border-gray-800'];

if ($sm) {
    $class[] = 'tw-px-1.5 tw-py-1 tw-text-xs';
} else {
    $class[] = 'tw-px-1.5 tw-py-1 tw-text-xs md:tw-px-4 md:tw-py-1.5 md:tw-text-sm';
}
@endphp


<button {{ $attributes->class($class) }}>
    <x-icons.arrow-counter-clockwise class="tw-mr-1.5 tw-h-4 tw-w-4 tw-fill-current">
    </x-icons.arrow-counter-clockwise>
    {{ $slot }}
</button>
