@if (is_empty($label))
    @if (!is_empty($label_format))
        @php $label = strtr($label_format, ['%name%' => $name, '%id%' => $id]) @endphp
    @else
        @php $label = utrans($translation_domain ? "$translation_domain.$name" : $name) @endphp
    @endif
@endif
<button type="{{ isset($type) ? $type : 'button' }}" @include('form.default.button_attributes')>{{ $label }}</button>