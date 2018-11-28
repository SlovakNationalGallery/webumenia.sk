@php $type = isset($type) ? $type : 'password' @endphp
@include('form.default.form_widget_simple', ['type' => $type])