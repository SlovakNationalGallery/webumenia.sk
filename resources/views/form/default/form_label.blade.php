@if ($label !== false)
    @if (!$compound)
        @php $label_attr = array_merge($label_attr, ['for' => $id]) @endphp
    @endif
    @if ($required)
        @php $class = trim(($label_attr['class'] ?? '') . ' required') @endphp
        @php $label_attr = array_merge($label_attr, ['class' => $class]) @endphp
    @endif
    @if (is_empty($label))
        @if (!is_empty($label_format))
            @php $label = strtr($label_format, ['%name%' => $name, '%id%' => $id]) @endphp
        @else
            {{-- fix https://github.com/laravel/framework/issues/2249 --}}
            @php $label = trans($translation_domain ? "$translation_domain.$name" : $name) @endphp
        @endif
    @endif
    <label
    @if ($label_attr)
        @include('form.default.attributes', ['attr' => $label_attr])
    @endif
    >{{ $label }}</label>
@endif