@php $type = isset($type) ? $type : 'hidden' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}