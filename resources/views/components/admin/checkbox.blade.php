@props(['name'])

<input type="hidden" name="{{ $name }}" value="0">
<input type="checkbox" name="{{ $name }}" value="1" {{ $attributes }}>
