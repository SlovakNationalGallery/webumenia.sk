@php $type = isset($type) ? $type : 'text' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}