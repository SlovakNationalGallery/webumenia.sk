@php $type = isset($type) ? $type : 'email' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}