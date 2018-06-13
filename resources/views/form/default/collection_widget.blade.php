@if (isset($prototype))
    @php $attr = array_merge($attr, ['data-prototype' => FormRenderer::row($prototype) ]) @endphp
@endif
{{--{!! FormRenderer::block($form, 'form_widget', get_defined_vars()) !!}--}}
@include('form_widget')