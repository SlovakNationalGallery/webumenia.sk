@if (isset($id))
    id="{{ $id }}"
@endif
{{--{!! FormRenderer::block($form, 'attributes', get_defined_vars()) !!}--}}
@include('attributes')