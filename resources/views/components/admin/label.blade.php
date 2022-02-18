@props(['value'])

<label {{ $attributes->merge(['class' => 'tw-block tw-text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
