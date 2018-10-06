@php $type = isset($type) ? $type : 'range' @endphp
@include('form.default.form_widget_simple', ['type' => $type])