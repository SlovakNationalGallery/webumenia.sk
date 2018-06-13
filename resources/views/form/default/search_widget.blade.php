@php $type = isset($type) ? $type : 'search' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}