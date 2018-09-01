@if ($label !== false)
    @if (!$compound)
        @php $label_attr = array_merge($label_attr, ['for' => $id]) @endphp
    @endif
    @if ($required)
        @php $class = isset($label_attr['class']) ? trim($label_attr['class'] . ' required') : 'required'; @endphp
        @php $label_attr = array_merge($label_attr, ['class' => $class]) @endphp
    @endif
    @if (empty($label))
        @if (!empty($label_format))
            @php $label = strtr($label_format, ['%name%' => $name, '%id%' => $id]) @endphp
        @else
            @php $label = FormRenderer::humanize($name) @endphp
        @endif
    @endif
    <label
    @if ($label_attr)
        @include('form::attributes', ['attr' => $label_attr])
    @endif
    >{{ $label }}</label>
@endif