@php $type = isset($type) ? $type : 'range' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}