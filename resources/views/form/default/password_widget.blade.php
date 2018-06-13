@php $type = isset($type) ? $type : 'password' @endphp
{!! FormRenderer::block($form, 'form_widget_simple', ['type' => $type]) !!}