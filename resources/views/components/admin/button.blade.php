@props([
    'primary' => false,
    'link' => null,
])

@php
$class = [
    'tw-font-medium tw-py-2 tw-px-4 tw-rounded active:tw-shadow-inner',
    'active:tw-outline active:tw-outline-black' => !$link,
    'tw-inline-block' => $link,
    'tw-text-white tw-bg-[#428bca] tw-border-[#357ebd] hover:tw-bg-[#3276b1] hover:tw-border-[#285e8e]' => $primary,
    'tw-border-gray-300 tw-border hover:tw-bg-gray-200' => !$primary,
];
@endphp

@if ($link)
    <a href="{{ $link }}" {{ $attributes->class($class) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->class($class)->merge(['type' => 'submit']) }}>
        {{ $slot }}
    </button>
@endif
