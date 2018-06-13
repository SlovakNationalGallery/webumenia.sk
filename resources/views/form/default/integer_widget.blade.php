@php $type = isset($type) ? $type : 'number' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}