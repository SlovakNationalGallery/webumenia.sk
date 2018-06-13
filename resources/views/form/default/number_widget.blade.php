{{-- type="number" doesn't work with floats --}}
@php $type = isset($type) ? $type : 'text' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}