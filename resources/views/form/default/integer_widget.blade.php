@php $type = isset($type) ? $type : 'number' @endphp
@include('form.default.form_widget_simple', ['type' => $type])