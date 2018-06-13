@if (empty($label))
    @if (!empty($label_format))
        @php $label = strtr($label_format, ['%name%' => $name, '%id%' => $id]) @endphp
    @else
        @php $label = FormRenderer::humanize($name) @endphp
    @endif
@endif
<button type="{{ isset($type) ? $type : 'button' }}" {!! FormRenderer::block($form, 'button_attributes') !!}>{{ $label }}</button>