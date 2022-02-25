@props([
    'outline' => false,
    'primary' => false,
    'danger' => false,
    'sm' => false,
    'link' => null,
])

@php
$defaultColor = !$primary && !$danger;
$defaultSize = $sm == false;

$class = [
    'tw-font-medium tw-rounded active:tw-shadow-inner',

    // Set outlines on buttons, not on links
    'active:tw-outline active:tw-outline-black' => !$link,
    'tw-inline-block' => $link,
];

if ($sm) {
    $class[] = 'tw-py-1 tw-px-2 tw-text-xs';
} else {
    $class[] = 'tw-py-2 tw-px-4';
}

if ($outline) {
    $class[] = 'tw-border';
    if ($primary) {
        $class[] = 'tw-text-[#3276b1] hover:tw-text-white tw-border-[#357ebd] hover:tw-bg-[#3276b1] hover:tw-border-[#285e8e]';
    }
    if ($danger) {
        $class[] = 'tw-text-[#d9534f] hover:tw-text-white tw-border-[#d43f3a] hover:tw-bg-[#d2322d] hover:tw-border-[#ac2925]';
    }
    if ($defaultColor) {
        $class[] = 'tw-border-gray-300 hover:tw-bg-gray-200';
    }
} else {
    if ($primary) {
        $class[] = 'tw-text-white tw-bg-[#428bca] tw-border-[#357ebd] hover:tw-bg-[#3276b1] hover:tw-border-[#285e8e]';
    }
    if ($danger) {
        $class[] = 'tw-text-white tw-bg-[#d9534f] hover:tw-text-white tw-border-[#d43f3a] hover:tw-bg-[#d2322d] hover:tw-border-[#ac2925]';
    }
    if ($defaultColor) {
        $class[] = 'tw-border-gray-300 tw-border hover:tw-bg-gray-200';
    }
}

if ($sm) {
}
@endphp

@if ($link)
    <a href="{{ $link }}" {{ $attributes->class($class) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->class($class)->merge(['type' => 'submit']) }}>
        {{ $slot }}
    </button>
@endif
