@php $type = isset($type) ? $type : 'url' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}